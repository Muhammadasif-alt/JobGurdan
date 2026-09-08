<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Public-facing directory of job seekers (talent pool).
 *
 * Everything shown here comes from what the seeker typed into their own
 * profile. Fields they have not filled in are returned empty and the views
 * leave them out — the directory only claims what a candidate has claimed
 * for themselves.
 */
class JobSeekerPublicController extends Controller
{
    /**
     * The seeker's own profile, shaped for the public views.
     *
     * @return array{headline: ?string, skills: list<string>, city: ?string, experience_years: ?int, open_to: ?string, links: array<string, string>}
     */
    public static function profileFor(User $user): array
    {
        return [
            'headline' => trim((string) $user->headline) ?: null,
            'skills' => $user->skillList(),
            'city' => trim((string) $user->preferred_city) ?: null,
            'experience_years' => $user->experience_years,
            'open_to' => trim((string) $user->open_to) ?: null,
            'links' => $user->profileLinks(),
        ];
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));

        $query = User::where('role', User::ROLE_JOB_SEEKER)
            ->where('is_active', true);

        if ($search !== '') {
            // The page promises search by name, username or skill.
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%")
                    ->orWhere('headline', 'like', "%{$search}%");
            });
        }

        $seekers = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $stats = Cache::remember('jobSeekersPage.stats', 600, function () {
            return [
                'total_seekers' => User::where('role', User::ROLE_JOB_SEEKER)->where('is_active', true)->count(),
                'open_jobs' => Job::count(),
                'companies' => User::where('role', User::ROLE_COMPANY)->count(),
            ];
        });

        return view('user.job-seekers.index', compact('seekers', 'stats', 'search'));
    }

    public function show(string $username)
    {
        $seeker = User::where('username', $username)
            ->where('role', User::ROLE_JOB_SEEKER)
            ->where('is_active', true)
            ->firstOrFail();

        $profile = self::profileFor($seeker);

        // A few "more talent" cards for the bottom of the detail page.
        $relatedSeekers = User::where('role', User::ROLE_JOB_SEEKER)
            ->where('is_active', true)
            ->where('id', '!=', $seeker->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('user.job-seekers.show', compact('seeker', 'profile', 'relatedSeekers'));
    }
}
