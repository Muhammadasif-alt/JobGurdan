<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Deterministic, fast job recommendations for job seekers.
 *
 * Scoring is pure PHP (no AI API calls) so it works in real time even
 * across thousands of jobs. The signals are:
 *
 *   - Skills overlap    (highest weight — direct skill keyword in position/description)
 *   - Title alignment   (seeker's headline words found in job position)
 *   - Location match    (seeker's preferred_city == job location/area)
 *   - Job-type match    (seeker's open_to == job.job_type)
 *   - Recency boost     (small bonus for jobs posted in the last 14 days)
 */
class JobRecommendationService
{
    public const MAX_SCORE = 100;

    /**
     * Build a recommendations query for a seeker, ranked by score.
     * Returns a query builder (chain ->paginate(), ->get(), etc.).
     */
    public function queryFor(User $user, ?array $filters = []): Builder
    {
        $query = Job::with(['advertiser', 'category', 'location'])
            ->where(function ($q) {
                $q->where('status', 'active')->orWhereNull('status');
            });

        // Apply seeker-provided filters
        if (! empty($filters['location'])) {
            $loc = $filters['location'];
            $query->whereHas('location', function ($q) use ($loc) {
                if (is_numeric($loc)) {
                    $q->where('locations.id', $loc);
                } else {
                    $q->where('name', $loc);
                }
            });
        }
        if (! empty($filters['job_type'])) {
            $query->where('job_type', $filters['job_type']);
        }
        if (! empty($filters['keywords'])) {
            $kw = trim($filters['keywords']);
            $query->where(function ($q) use ($kw) {
                $q->where('position', 'like', "%{$kw}%")
                    ->orWhere('description', 'like', "%{$kw}%");
            });
        }

        // Default order — latest first; scoring is layered after we have the rows.
        $query->latest('id');

        return $query;
    }

    /**
     * Score a single job for a given seeker (0–100).
     */
    public function scoreJob(Job $job, User $user): array
    {
        $weights = [
            'skills' => 50,
            'title' => 20,
            'location' => 15,
            'jobType' => 10,
            'recency' => 5,
        ];

        $reasons = [];
        $skillsScore = $this->skillsScore($job, $user, $reasons);
        $titleScore = $this->titleScore($job, $user, $reasons);
        $locationScore = $this->locationScore($job, $user, $reasons);
        $jobTypeScore = $this->jobTypeScore($job, $user, $reasons);
        $recencyScore = $this->recencyScore($job);

        $total =
            $weights['skills'] * $skillsScore +
            $weights['title'] * $titleScore +
            $weights['location'] * $locationScore +
            $weights['jobType'] * $jobTypeScore +
            $weights['recency'] * $recencyScore;

        return [
            'score' => (int) round($total),
            'reasons' => $reasons,
        ];
    }

    /**
     * Convenience: enrich a Collection<Job> in place with ->match_score
     * and ->match_reasons, then sort by score descending.
     */
    public function enrichAndSort(Collection $jobs, User $user): Collection
    {
        foreach ($jobs as $job) {
            $info = $this->scoreJob($job, $user);
            $job->match_score = $info['score'];
            $job->match_reasons = $info['reasons'];
        }

        return $jobs->sortByDesc('match_score')->values();
    }

    // ───────── scoring components — each returns 0..1 ─────────

    private function skillsScore(Job $job, User $user, array &$reasons): float
    {
        $skills = $this->parseSkills($user->skills);
        if (empty($skills)) {
            return 0.0;
        }
        $haystack = mb_strtolower(($job->position ?? '').' '.($job->description ?? ''));
        $matched = [];
        foreach ($skills as $skill) {
            $needle = mb_strtolower($skill);
            if ($needle === '') {
                continue;
            }
            if (str_contains($haystack, $needle)) {
                $matched[] = $skill;
            }
        }
        if (empty($matched)) {
            return 0.0;
        }
        $reasons[] = count($matched).' of your skills match ('.implode(', ', array_slice($matched, 0, 4)).(count($matched) > 4 ? '…' : '').')';

        // Saturate at 5 skills — beyond that diminishing returns
        return min(1.0, count($matched) / 5);
    }

    private function titleScore(Job $job, User $user, array &$reasons): float
    {
        $headline = trim((string) $user->headline);
        if ($headline === '') {
            return 0.0;
        }
        $words = collect(preg_split('/[\s,\-\/]+/', mb_strtolower($headline)))
            ->filter(fn ($w) => mb_strlen($w) >= 3)
            ->reject(fn ($w) => in_array($w, ['the', 'and', 'with', 'for', 'developer', 'engineer']))
            ->unique()
            ->take(6)
            ->all();
        if (empty($words)) {
            return 0.0;
        }
        $position = mb_strtolower($job->position ?? '');
        $hits = 0;
        foreach ($words as $w) {
            if (str_contains($position, $w)) {
                $hits++;
            }
        }
        if ($hits === 0) {
            return 0.0;
        }
        $reasons[] = 'Job title matches your headline';

        return min(1.0, $hits / max(2, count($words)));
    }

    private function locationScore(Job $job, User $user, array &$reasons): float
    {
        $pref = mb_strtolower(trim((string) $user->preferred_city));
        if ($pref === '') {
            return 0.0;
        }
        $locName = mb_strtolower((string) optional($job->location)->name);
        $locArea = mb_strtolower((string) optional($job->location)->area);

        if ($locName !== '' && (str_contains($locName, $pref) || str_contains($pref, $locName))) {
            $reasons[] = 'In your preferred location';

            return 1.0;
        }
        if ($locArea !== '' && (str_contains($locArea, $pref) || str_contains($pref, $locArea))) {
            $reasons[] = 'In your preferred area';

            return 0.8;
        }

        return 0.0;
    }

    private function jobTypeScore(Job $job, User $user, array &$reasons): float
    {
        $openTo = trim((string) $user->open_to);
        if ($openTo === '' || empty($job->job_type)) {
            return 0.0;
        }
        $openParts = collect(explode(',', mb_strtolower($openTo)))
            ->map(fn ($p) => trim($p))
            ->filter()
            ->all();
        $jobType = mb_strtolower((string) $job->job_type);
        foreach ($openParts as $p) {
            if ($p !== '' && str_contains($jobType, $p)) {
                $reasons[] = 'Matches your "'.$job->job_type.'" preference';

                return 1.0;
            }
        }

        return 0.0;
    }

    private function recencyScore(Job $job): float
    {
        if (! $job->created_at) {
            return 0.0;
        }
        $days = $job->created_at->diffInDays(now());
        if ($days <= 1) {
            return 1.0;
        }
        if ($days <= 7) {
            return 0.75;
        }
        if ($days <= 14) {
            return 0.5;
        }
        if ($days <= 30) {
            return 0.25;
        }

        return 0.0;
    }

    /** Normalize seeker skills (DB stores as comma/newline/pipe separated string). */
    private function parseSkills(?string $skills): array
    {
        if (! $skills) {
            return [];
        }

        return collect(preg_split('/[\n,|;]+/', $skills))
            ->map(fn ($s) => trim($s))
            ->filter(fn ($s) => mb_strlen($s) >= 2)
            ->unique()
            ->values()
            ->all();
    }
}
