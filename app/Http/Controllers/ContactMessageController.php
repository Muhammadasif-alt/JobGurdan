<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Services\AiContentService;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function __construct(protected AiContentService $ai)
    {
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $status = $request->input('status');
        $spam   = $request->input('spam'); // 'yes' | 'no' | null

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
            'total'   => ContactMessage::count(),
            'new'     => ContactMessage::where('status', 'new')->count(),
            'read'    => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'spam'    => ContactMessage::where('is_spam', true)->count(),
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
        'seo boost', 'seo service', 'rank higher', 'first page of google',
        'increase google organic', 'increase your traffic', 'drive more qualified traffic',
        'website traffic', 'guest post', 'backlink', 'back link', 'link building',
        'cheap seo', 'website ranking', 'top of google', 'serp', 'pbn',
        'crypto', 'bitcoin', 'investment opportunity',
        'jojfw', 'jojfwi', 'foekdwd', // gibberish chunks seen in the current spam wave
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
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
        ]);

        // 3. Keyword filter — drop messages containing typical SEO-spam phrases
        $haystack = mb_strtolower(($validated['subject'] ?? '').' '.($validated['message'] ?? ''));
        foreach (self::SPAM_PATTERNS as $needle) {
            if (str_contains($haystack, $needle)) {
                return redirect('/')->with('success', 'Thank you for your message! We will get back to you soon.');
            }
        }

        // 4. Per-IP rate limit — same IP can't submit more than 3 messages per hour
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
                'is_spam'     => $result['is_spam'],
                'spam_score'  => $result['score'],
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
