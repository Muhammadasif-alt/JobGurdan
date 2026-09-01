<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AiContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

/**
 * AJAX endpoints for AI-assisted content generation across the site.
 * Each endpoint is gated by authenticated user + per-user rate limit.
 */
class AiAssistantController extends Controller
{
    public function __construct(protected AiContentService $ai)
    {
    }

    /** Generate a job description from a title + optional context. */
    public function jobDescription(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->throttle('ai-jobdesc', 10, 60);

        $data = $request->validate([
            'title'    => ['required', 'string', 'max:191'],
            'company'  => ['nullable', 'string', 'max:191'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:191'],
        ]);

        $text = $this->ai->generateJobDescription(
            $data['title'],
            $data['company']  ?? null,
            $data['keywords'] ?? null,
            $data['location'] ?? null,
        );

        return $this->respond($text, 'description');
    }

    /** Polish or generate a seeker bio. */
    public function polishBio(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->throttle('ai-bio', 15, 60);

        $data = $request->validate([
            'bio'      => ['nullable', 'string', 'max:5000'],
            'headline' => ['nullable', 'string', 'max:191'],
            'skills'   => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $text = $this->ai->polishBio(
            $data['bio']      ?? null,
            $data['headline'] ?? $user?->headline,
            $data['skills']   ?? $user?->skills,
            $user?->experience_years ? (string) $user->experience_years : null,
        );

        return $this->respond($text, 'bio');
    }

    /** Polish or generate a seeker headline. */
    public function polishHeadline(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->throttle('ai-headline', 15, 60);

        $data = $request->validate([
            'headline' => ['nullable', 'string', 'max:191'],
            'skills'   => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $text = $this->ai->polishHeadline(
            $data['headline'] ?? null,
            $data['skills']   ?? $user?->skills,
            $user?->experience_years ? (string) $user->experience_years : null,
        );

        return $this->respond($text, 'headline');
    }

    /** Extract skills CSV from free-form text. */
    public function extractSkills(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->throttle('ai-skills', 15, 60);

        $data = $request->validate([
            'source' => ['required', 'string', 'max:8000'],
        ]);

        $text = $this->ai->extractSkills($data['source']);
        return $this->respond($text, 'skills');
    }

    /** Generate category description (admin only). */
    public function categoryDescription(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->ensureAdmin();
        $this->throttle('ai-category', 10, 60);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $text = $this->ai->generateCategoryDescription($data['name'], $data['type'] ?? 'job category');
        return $this->respond($text, 'description');
    }

    /** Generate full blog article content (admin only). */
    public function blogContent(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->ensureAdmin();
        $this->throttle('ai-blog-content', 6, 60);

        $data = $request->validate([
            'title'    => ['required', 'string', 'max:191'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'tone'     => ['nullable', 'string', 'max:60'],
        ]);

        $text = $this->ai->generateBlogContent(
            $data['title'],
            $data['keywords'] ?? null,
            $data['tone'] ?? null,
        );

        return $this->respond($text, 'content');
    }

    /** Generate blog excerpt (admin only). */
    public function blogExcerpt(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->ensureAdmin();
        $this->throttle('ai-blog-excerpt', 15, 60);

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:191'],
            'content' => ['nullable', 'string', 'max:20000'],
        ]);

        $text = $this->ai->generateBlogExcerpt($data['title'], $data['content'] ?? null);
        return $this->respond($text, 'excerpt');
    }

    /** Generate blog SEO meta (admin only). Returns {meta_title, meta_description}. */
    public function blogMeta(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->ensureAdmin();
        $this->throttle('ai-blog-meta', 15, 60);

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:191'],
            'content' => ['nullable', 'string', 'max:20000'],
        ]);

        $meta = $this->ai->generateBlogMeta($data['title'], $data['content'] ?? null);
        if (! $meta) {
            return response()->json(['ok' => false, 'message' => 'AI did not return a valid response.'], 422);
        }
        return response()->json(['ok' => true, 'data' => $meta]);
    }

    /** Generic free-form AI text generation (admin only). */
    public function generic(Request $request): JsonResponse
    {
        $this->ensureConfigured();
        $this->ensureAdmin();
        $this->throttle('ai-generic', 10, 60);

        $data = $request->validate([
            'prompt'      => ['required', 'string', 'max:4000'],
            'max_tokens'  => ['nullable', 'integer', 'min:50', 'max:3000'],
            'temperature' => ['nullable', 'numeric', 'min:0', 'max:1'],
        ]);

        $text = $this->ai->generateText($data['prompt'], [
            'maxOutputTokens' => (int) ($data['max_tokens']  ?? 1200),
            'temperature'     => (float) ($data['temperature'] ?? 0.6),
        ]);

        return $this->respond($text, 'text');
    }

    /* ============================================================
     | helpers
     ============================================================ */

    protected function ensureConfigured(): void
    {
        abort_unless($this->ai->isConfigured(), 503, 'AI is not configured on this server.');
    }

    protected function ensureAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->role === User::ROLE_ADMIN, 403);
    }

    protected function throttle(string $key, int $maxAttempts, int $decaySeconds): void
    {
        $signature = $key.':'.(Auth::id() ?? request()->ip());
        if (RateLimiter::tooManyAttempts($signature, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($signature);
            abort(429, "Too many AI requests. Try again in {$seconds}s.");
        }
        RateLimiter::hit($signature, $decaySeconds);
    }

    protected function respond(?string $text, string $key): JsonResponse
    {
        if (! $text) {
            return response()->json([
                'ok'      => false,
                'message' => 'AI did not return a result. Please try again.',
            ], 422);
        }

        return response()->json([
            'ok'   => true,
            'data' => [$key => $text],
        ]);
    }
}
