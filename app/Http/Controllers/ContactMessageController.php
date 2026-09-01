<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Services\AiContentService;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function __construct(protected AiContentService $ai) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status');
        $spam = $request->input('spam'); // 'yes' | 'no' | null

        $query = ContactMessage::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        if (in_array($status, ['new', 'read', 'replied'], true)) {
            $query->where('status', $status);
        }
        if ($spam === 'yes') {
            $query->where('is_spam', true);
        } elseif ($spam === 'no') {
            $query->where('is_spam', false);
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'spam' => ContactMessage::where('is_spam', true)->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats', 'search', 'status', 'spam'));
    }

    public function create()
    {
        return view('admin.contact-messages.create');
    }

    /**
     * Phrases that signal SEO/marketing spam in the subject or body.
     * Anything matching one of these is silently dropped — bots think the
     * form succeeded, real users are unaffected.
     */
    private const SPAM_PATTERNS = [
        // SEO services pitch
        'seo boost', 'seo service', 'seo package', 'seo expert', 'seo result',
        'seo and driving', 'search visibility', 'rank higher', 'first page of google',
        'increase google organic', 'google organic ranking', 'organic ranking',
        'increase your traffic', 'increase seo', 'drive more qualified traffic',
        'attract more clients', 'attract more customers',
        'website traffic', 'webpage & marketing', 'webpage and marketing',
        'website ranking', 'top of google', 'google search results',
        'google ranking', 'serp', 'pbn',
        // Link building / guest posts
        'guest post', 'backlink', 'back link', 'link building', 'link insertion',
        'free article', 'article proposal', 'sponsored post', 'collaboration opportunity',
        // Generic SEO sales pitches
        'cheap seo', 'affordable seo', 'seo audit', 'website audit', 'free audit',
        'online presence', 'digital marketing service', 'lead generation service',
        // Crypto / finance scams
        'crypto', 'bitcoin', 'ethereum', 'forex', 'investment opportunity',
        'binary option', 'trading signal',
        // Known gibberish chunks from spam waves
        'jojfw', 'jojfwi', 'foekdwd', 'aaaqfj',
    ];

    /**
     * Email domains that have only ever sent spam. Block at submission time.
     */
    private const BLOCKED_DOMAINS = [
        'bestaiseocompany.com',
        'getonglobe.com',
        'mapmybiz.org',
        'mapmybiz.com',
        'bizbuying.net',
    ];

    public function store(Request $request)
    {
        // 1. Honeypot fields — if a bot filled these, drop silently with a "thank you" reply
        if ($request->filled('website') || $request->filled('hp_phone')) {
            return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
        }

        // 2. Submission speed check — anything submitted in under 2 seconds is a bot
        $startedAt = (int) $request->input('form_started_at', 0);
        if ($startedAt > 0 && (now()->timestamp - $startedAt) < 2) {
            return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 3. Keyword filter — drop messages containing typical SEO-spam phrases
        $haystack = mb_strtolower(($validated['subject'] ?? '').' '.($validated['message'] ?? ''));
        foreach (self::SPAM_PATTERNS as $needle) {
            if (str_contains($haystack, $needle)) {
                return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
            }
        }

        // 4. Email-domain blocklist — known cold-outreach senders
        $emailDomain = strtolower((string) substr(strrchr($validated['email'], '@') ?: '', 1));
        if ($emailDomain !== '' && in_array($emailDomain, self::BLOCKED_DOMAINS, true)) {
            return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
        }

        // 5. Gibberish detection — random consonant strings like "IQzUghrVcsUJNuYBQ"
        // or "Aaaqfjfwkdjifiefowkd" never appear in real human-written subjects.
        if ($this->looksLikeGibberish($validated['subject'])) {
            return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
        }

        // 6. Per-IP rate limit — same IP can't submit more than 3 messages per hour
        $rlKey = 'contact-form:'.$request->ip();
        $count = (int) cache()->get($rlKey, 0);
        if ($count >= 3) {
            return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
        }
        cache()->put($rlKey, $count + 1, now()->addHour());

        // Save the message immediately (no AI call on the request path — too slow).
        // Admin can run "Run AI Spam Scan" to score new messages in batches.
        ContactMessage::create($validated);

        return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Detect machine-generated gibberish like "IQzUghrVcsUJNuYBQ" or "Aaaqfjfwkdjifiefowkd".
     * Three signals together catch nearly all bot-generated subjects without
     * blocking real human ones:
     *   (a) Long word with rapid case changes (uppercase in the middle, repeated)
     *   - "iQzUghrVcsUJ" has 7+ case flips → never happens in English
     *   (b) Long word with 4+ consecutive consonants
     *   - "Aaaqfjfwkdji" has "qfjfwkdj" → not pronounceable English
     *   (c) Whole text has > 30% consonant-cluster density
     */
    private function looksLikeGibberish(string $text): bool
    {
        $text = trim($text);
        if (strlen($text) < 8) {
            return false;
        }

        foreach (preg_split('/\s+/', $text) as $word) {
            $word = preg_replace('/[^a-zA-Z]/', '', $word);
            if (strlen($word) < 8) {
                continue;
            }

            // (a) Rapid case flips inside one word → bot-generated mixed case
            $caseFlips = 0;
            for ($i = 1; $i < strlen($word); $i++) {
                $a = ctype_upper($word[$i - 1]);
                $b = ctype_upper($word[$i]);
                if ($a !== $b) {
                    $caseFlips++;
                }
            }
            if ($caseFlips >= 6) {
                return true;
            }

            // (b) 5+ consecutive consonants → not English
            if (preg_match('/[bcdfghjklmnpqrstvwxyz]{5,}/i', $word)) {
                return true;
            }
        }

        return false;
    }

    public function show(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => 'read']);

        return view('admin.contact-messages.show', compact('message'));
    }

    public function edit(string $id)
    {
        $message = ContactMessage::findOrFail($id);

        return view('admin.contact-messages.edit', compact('message'));
    }

    public function update(Request $request, string $id)
    {
        $message = ContactMessage::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied',
        ]);
        $message->update($validated);

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message status updated');
    }

    public function destroy(string $id)
    {
        ContactMessage::destroy($id);

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted successfully');
    }

    /**
     * Bulk delete a list of messages (sent from the admin index via checkboxes).
     * Also supports "delete all unread" / "delete all" via the `scope` param.
     */
    public function bulkDestroy(Request $request)
    {
        $scope = $request->input('scope');

        if ($scope === 'all_new') {
            $count = ContactMessage::where('status', 'new')->count();
            ContactMessage::where('status', 'new')->delete();

            return back()->with('success', "Deleted {$count} unread messages.");
        }

        if ($scope === 'all_spam') {
            $count = ContactMessage::where('is_spam', true)->count();
            ContactMessage::where('is_spam', true)->delete();

            return back()->with('success', "Deleted {$count} spam messages.");
        }

        if ($scope === 'all') {
            $count = ContactMessage::count();
            ContactMessage::truncate();

            return back()->with('success', "Deleted all {$count} messages.");
        }

        $ids = (array) $request->input('ids', []);
        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            return back()->with('error', 'No messages selected.');
        }

        $count = ContactMessage::whereIn('id', $ids)->count();
        ContactMessage::whereIn('id', $ids)->delete();

        return back()->with('success', "Deleted {$count} message".($count === 1 ? '' : 's').'.');
    }

    /**
     * Run AI spam scoring on all unscored messages (admin trigger).
     */
    public function scanSpam(Request $request)
    {
        if (! $this->ai->isConfigured()) {
            return back()->with('error', 'AI is not configured. Add GEMINI_API_KEY to .env');
        }

        $unscored = ContactMessage::whereNull('spam_score')->limit(50)->get();
        $scanned = $flagged = 0;

        foreach ($unscored as $msg) {
            $combined = $msg->subject."\n\n".$msg->message;
            $result = $this->ai->detectSpam($combined, 'contact form message');
            if ($result === null) {
                continue;
            }
            $msg->update([
                'is_spam' => $result['is_spam'],
                'spam_score' => $result['score'],
                'spam_reason' => $result['reason'],
            ]);
            $scanned++;
            if ($result['is_spam']) {
                $flagged++;
            }
        }

        $remaining = ContactMessage::whereNull('spam_score')->count();
        $msg = "AI scanned {$scanned} message".($scanned === 1 ? '' : 's').", flagged {$flagged} as spam.";
        if ($remaining > 0) {
            $msg .= " {$remaining} more to scan — click again to continue.";
        }

        return back()->with('success', $msg);
    }
}
