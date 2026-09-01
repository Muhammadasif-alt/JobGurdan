<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\AiResumeParser;
use App\Services\ResumeParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SeekerProfileController extends Controller
{
    /** Editable seeker profile + skill chips. */
    public function show()
    {
        $user = Auth::user();

        return view('seeker.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:30'],
            'headline' => ['nullable', 'string', 'max:191'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'preferred_city' => ['nullable', 'string', 'max:120'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'open_to' => ['nullable', 'string', 'max:60'],
        ]);

        Auth::user()->update($data);

        \Illuminate\Support\Facades\Cache::forget('seekerDash.aiMatch.'.Auth::id());

        return redirect()->route('seeker.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /** Resume / CV upload + download + remove. */
    public function resume()
    {
        $user = Auth::user();

        return view('seeker.resume', compact('user'));
    }

    public function uploadResume(Request $request, ResumeParser $parser, AiResumeParser $ai)
    {
        $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5 MB
        ], [
            'cv.mimes' => 'Only PDF, DOC and DOCX files are allowed.',
            'cv.max' => 'File must be 5 MB or smaller.',
        ]);

        $user = Auth::user();

        // Remove the previous CV if there was one
        if ($user->cv_path && Storage::disk('public')->exists($user->cv_path)) {
            Storage::disk('public')->delete($user->cv_path);
        }

        $file = $request->file('cv');
        $path = $file->store('resumes/'.$user->id, 'public');

        $absPath = Storage::disk('public')->path($path);
        $ext = strtolower($file->getClientOriginalExtension());

        // Get raw text first (works for both AI and regex parsers)
        $rawText = $parser->extractText($absPath, $ext);

        // === AI-powered section parsing (preferred when API key is set) ===
        // Falls back to regex parser if Gemini isn't configured or call fails.
        if ($ai->isConfigured() && $rawText !== '') {
            $sections = $ai->parseSections($rawText);
            $extracted = $ai->extractProfileFields($rawText);
        } else {
            $sections = $parser->parseSections($absPath, $ext);
            $extracted = $parser->parse($absPath, $ext);
        }

        $user->update([
            'cv_path' => $path,
            'resume_data' => $sections,
        ]);

        \Illuminate\Support\Facades\Cache::forget('seekerDash.aiMatch.'.$user->id);

        $autoFillable = ['phone', 'headline', 'bio', 'skills', 'preferred_city', 'experience_years'];
        $filled = [];
        $updates = [];
        foreach ($autoFillable as $field) {
            $current = trim((string) $user->{$field});
            $newVal = $extracted[$field] ?? null;
            if ($current === '' && $newVal !== null && $newVal !== '') {
                $updates[$field] = $newVal;
                $filled[] = $field;
            }
        }
        // Name only fills when current name looks like a placeholder username
        if (isset($extracted['name']) && (empty($user->name) || $user->name === $user->username)) {
            $updates['name'] = $extracted['name'];
            $filled[] = 'name';
        }
        if (! empty($updates)) {
            $user->update($updates);
        }

        $message = 'Resume uploaded â€” employers can now view it.';
        if (! empty($filled)) {
            $labels = [
                'name' => 'name',
                'phone' => 'phone',
                'headline' => 'headline',
                'bio' => 'about',
                'skills' => 'skills',
                'preferred_city' => 'preferred city',
                'experience_years' => 'experience',
            ];
            $list = collect($filled)->map(fn ($f) => $labels[$f] ?? $f)->implode(', ');
            $message .= ' We auto-filled: '.$list.'. Review &amp; complete the rest in your profile.';
        }

        return redirect()->route('seeker.resume')->with('success', $message);
    }

    /**
     * Update the auto-extracted resume sections (lets the user fix things the
     * parser got wrong without re-uploading the CV).
     *
     * Bullets and per-item bullets arrive as one-per-line strings from textareas,
     * which we explode here so the saved JSON keeps the same array shape as the
     * parser produced.
     */
    public function updateResumeData(Request $request)
    {
        $user = Auth::user();

        $payload = $request->validate([
            'sections' => ['nullable', 'array'],
            'sections.*.heading' => ['nullable', 'string', 'max:191'],
            'sections.*.type' => ['nullable', 'string', 'max:32'],
            'sections.*.paragraphs_text' => ['nullable', 'string', 'max:20000'],
            'sections.*.bullets_text' => ['nullable', 'string', 'max:20000'],
            'sections.*.items' => ['nullable', 'array'],
            'sections.*.items.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.subtitle' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.meta' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.paragraphs_text' => ['nullable', 'string', 'max:20000'],
            'sections.*.items.*.bullets_text' => ['nullable', 'string', 'max:20000'],
        ]);

        $splitLines = function (?string $text): array {
            if (! $text) {
                return [];
            }

            return collect(preg_split('/\r?\n/', $text))
                ->map(fn ($line) => trim((string) $line))
                ->filter()
                ->values()
                ->all();
        };
        $splitParagraphs = function (?string $text): array {
            if (! $text) {
                return [];
            }

            return collect(preg_split('/\n\s*\n/', $text))
                ->map(fn ($p) => trim((string) $p))
                ->filter()
                ->values()
                ->all();
        };

        $sections = [];
        foreach (($payload['sections'] ?? []) as $sec) {
            $heading = trim((string) ($sec['heading'] ?? ''));
            if ($heading === '') {
                continue;
            }

            $items = [];
            foreach (($sec['items'] ?? []) as $item) {
                $items[] = [
                    'title' => trim((string) ($item['title'] ?? '')),
                    'subtitle' => trim((string) ($item['subtitle'] ?? '')),
                    'meta' => trim((string) ($item['meta'] ?? '')),
                    'paragraphs' => $splitParagraphs($item['paragraphs_text'] ?? null),
                    'bullets' => $splitLines($item['bullets_text'] ?? null),
                ];
            }

            $sections[] = [
                'heading' => $heading,
                'type' => $sec['type'] ?? 'paragraph',
                'paragraphs' => $splitParagraphs($sec['paragraphs_text'] ?? null),
                'bullets' => $splitLines($sec['bullets_text'] ?? null),
                'items' => $items,
            ];
        }

        $resumeData = is_array($user->resume_data) ? $user->resume_data : [];
        $resumeData['sections'] = $sections;
        $user->update(['resume_data' => $resumeData]);

        \Illuminate\Support\Facades\Cache::forget('seekerDash.aiMatch.'.$user->id);

        return redirect()->route('seeker.profile')->with('success', 'Resume content updated.');
    }

    public function deleteResume()
    {
        $user = Auth::user();
        if ($user->cv_path && Storage::disk('public')->exists($user->cv_path)) {
            Storage::disk('public')->delete($user->cv_path);
        }
        $user->update(['cv_path' => null]);

        return redirect()->route('seeker.resume')
            ->with('success', 'Resume removed.');
    }

    /** Applications list (placeholder using session-stored applies). */
    public function applications()
    {
        $user = Auth::user();

        // Until a proper applications table exists, show a meaningful list
        // built from the user's recently-viewed/saved jobs as a stand-in.
        $appliedJobIds = session('applied_job_ids', []);

        $applications = Job::with(['advertiser', 'category', 'location'])
            ->whereIn('id', $appliedJobIds)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($job) {
                $job->applied_at = now()->subDays(rand(1, 14)); // placeholder timestamp
                $job->status_label = 'Pending Review';

                return $job;
            });

        return view('seeker.applications', compact('user', 'applications'));
    }
}
