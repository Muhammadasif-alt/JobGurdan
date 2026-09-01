<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Services\AiContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JobSeekerDashboardController extends Controller
{
    public function __construct(protected AiContentService $ai)
    {
    }

    /**
     * Job seeker dashboard — quick links, AI-matched jobs, and saved searches.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = Cache::remember('seekerDash.stats', 600, function () {
            return [
                'open_jobs'    => Job::where(function ($q) {
                                    $q->where('status', 'active')->orWhereNull('status');
                                })->count(),
                'this_week'    => Job::where('created_at', '>=', now()->subWeek())->count(),
                'companies'    => \App\Models\Advertiser::count(),
                'categories'   => Category::count(),
            ];
        });

        [$recommendedJobs, $aiMatched] = $this->buildRecommendations($user);

        $topCategories = Cache::remember('seekerDash.topCategories', 600, function () {
            return Category::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->take(8)
                ->get(['id', 'name', 'slug']);
        });

        $topLocations = Cache::remember('seekerDash.topLocations', 600, function () {
            return Location::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->take(8)
                ->get(['id', 'name', 'area']);
        });

        return view('seeker.dashboard', compact(
            'user', 'stats', 'recommendedJobs', 'aiMatched',
            'topCategories', 'topLocations'
        ));
    }

    /**
     * Returns [Collection $jobs, string $aiState] where $aiState is one of:
     *   'matched' — cached AI matches are ready and shown
     *   'pending' — AI eligible but not cached yet — view will lazy-load via AJAX
     *   'none'    — no AI (no profile or AI not configured) — show plain latest jobs
     */
    protected function buildRecommendations($user): array
    {
        $hasProfile = $user && (
            ! empty($user->skills) || ! empty($user->headline) || ! empty($user->bio)
        );

        $latestJobs = function () {
            return Job::with(['advertiser', 'category', 'location'])
                ->where(function ($q) { $q->where('status', 'active')->orWhereNull('status'); })
                ->latest()->take(6)->get();
        };

        if (! $hasProfile || ! $this->ai->isConfigured()) {
            return [$latestJobs(), 'none'];
        }

        // Only return cached results synchronously. Never call AI on dashboard load —
        // that's slow (3–8s). The view kicks off an AJAX request to /seeker/dashboard/ai-matches
        // which fills the cache, then refreshes the section.
        $cacheKey = 'seekerDash.aiMatch.'.$user->id;
        $cached = Cache::get($cacheKey);
        if (is_array($cached) && ! empty($cached['ids'])) {
            $jobs = Job::with(['advertiser', 'category', 'location'])
                ->whereIn('id', $cached['ids'])
                ->get()
                ->sortBy(fn($j) => array_search($j->id, $cached['ids']))
                ->values();
            foreach ($jobs as $job) {
                $info = $cached['scores'][$job->id] ?? null;
                $job->ai_score  = $info['score']  ?? null;
                $job->ai_reason = $info['reason'] ?? null;
            }
            return [$jobs, 'matched'];
        }

        return [$latestJobs(), 'pending'];
    }

    /**
     * AJAX endpoint: compute AI job matches for the current seeker.
     * Called by the dashboard once the page has rendered, so the initial
     * load isn't blocked by a 3–8s Gemini call.
     */
    public function aiMatches(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $this->ai->isConfigured()) {
            return response()->json(['ok' => false, 'message' => 'Not eligible'], 422);
        }

        $cacheKey = 'seekerDash.aiMatch.'.$user->id;
        $cached = Cache::get($cacheKey);

        if (! is_array($cached) || empty($cached['ids'])) {
            $candidates = Job::with(['advertiser', 'category', 'location'])
                ->where(function ($q) { $q->where('status', 'active')->orWhereNull('status'); })
                ->latest()->take(30)->get();

            if ($candidates->isEmpty()) {
                return response()->json(['ok' => true, 'jobs' => []]);
            }

            $seekerProfile = [
                'headline'         => $user->headline,
                'bio'              => $user->bio,
                'skills'           => $user->skills,
                'experience_years' => $user->experience_years,
                'preferred_city'   => $user->preferred_city,
                'open_to'          => $user->open_to,
            ];

            $jobsForAi = $candidates->map(fn ($j) => [
                'id'          => $j->id,
                'title'       => $j->position,
                'description' => mb_substr((string) $j->description, 0, 600),
                'location'    => optional($j->location)->name,
                'category'    => optional($j->category)->name,
                'job_type'    => $j->job_type,
            ])->values()->all();

            try {
                $scores = $this->ai->matchSeekerToJobs($seekerProfile, $jobsForAi);
            } catch (\Throwable $e) {
                return response()->json(['ok' => false, 'message' => 'AI call failed'], 500);
            }

            if (empty($scores)) {
                return response()->json(['ok' => true, 'jobs' => []]);
            }

            $ranked = $candidates
                ->map(function ($job) use ($scores) {
                    $info = $scores[$job->id] ?? null;
                    $job->ai_score  = $info['score']  ?? 0;
                    $job->ai_reason = $info['reason'] ?? null;
                    return $job;
                })
                ->sortByDesc('ai_score')
                ->take(6)->values();

            Cache::put($cacheKey, [
                'ids'    => $ranked->pluck('id')->all(),
                'scores' => $scores,
            ], now()->addHours(6));

            $cached = ['ids' => $ranked->pluck('id')->all(), 'scores' => $scores];
        }

        $jobs = Job::with(['advertiser', 'category', 'location'])
            ->whereIn('id', $cached['ids'])
            ->get()
            ->sortBy(fn ($j) => array_search($j->id, $cached['ids']))
            ->values()
            ->map(function ($job) use ($cached) {
                $info = $cached['scores'][$job->id] ?? null;
                return [
                    'id'        => $job->id,
                    'position'  => $job->position,
                    'company'   => optional($job->advertiser)->name ?? 'Company',
                    'location'  => optional($job->location)->name,
                    'category'  => optional($job->category)->name,
                    'when'      => optional($job->created_at)?->diffForHumans(),
                    'url'       => route('jobs.show', \Illuminate\Support\Str::slug($job->position.'-'.(optional($job->location)->name ?? ''))),
                    'ai_score'  => $info['score']  ?? null,
                    'ai_reason' => $info['reason'] ?? null,
                ];
            });

        return response()->json(['ok' => true, 'jobs' => $jobs]);
    }
}
