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
 * Profiles are derived from User rows (role = job_seeker). Headline,
 * skills and target location are generated deterministically from the
 * user's id so the same person sees the same "profile" on every refresh
 * — until we add real profile columns the experience still feels solid.
 */
class JobSeekerPublicController extends Controller
{
    /** Headline templates keyed by category — stable per-user via id hash. */
    public const HEADLINES = [
        'Warehouse & logistics specialist looking for new opportunities',
        'Experienced truck driver — open to long-haul or regional routes',
        'Registered nurse seeking healthcare positions in the U.S.',
        'Construction supervisor available for large-scale projects',
        'IT support specialist with 5+ years in helpdesk & networks',
        'Software developer fluent in PHP, JavaScript and React',
        'Data entry expert with high accuracy and fast turnaround',
        'Customer service rep with bilingual English/Spanish skills',
        'Digital marketer specializing in SEO and content strategy',
        'Senior accountant — CPA-track with audit experience',
        'Retail manager ready to lead a high-volume store',
        'Security professional looking for full-time guard positions',
        'Remote-friendly virtual assistant with admin & PM background',
        'Entry-level graduate eager to start in tech or finance',
        'Graphic designer with brand & UI portfolio',
    ];

    /** Skill chips keyed by index — picked deterministically. */
    public const SKILL_BANK = [
        ['Forklift', 'Inventory', 'Shipping', 'Pallet jack'],
        ['CDL Class A', 'DOT Compliance', 'Logistics', 'Long-haul'],
        ['BLS', 'Patient Care', 'Charting', 'Acute care'],
        ['OSHA 30', 'Blueprints', 'Project Mgmt', 'Site Safety'],
        ['Networking', 'Windows Server', 'Helpdesk', 'Active Directory'],
        ['PHP', 'Laravel', 'JavaScript', 'React', 'MySQL'],
        ['Excel', 'Google Sheets', '10-Key', 'Typing 80wpm'],
        ['Bilingual', 'CRM', 'Empathy', 'Phone Etiquette'],
        ['SEO', 'Google Ads', 'Content', 'Analytics'],
        ['QuickBooks', 'GAAP', 'Audit', 'Excel'],
        ['Visual Merchandising', 'P&L', 'Hiring', 'Scheduling'],
        ['CPR', 'De-escalation', 'Patrol', 'CCTV'],
        ['Calendar Mgmt', 'Notion', 'Slack', 'Travel Planning'],
        ['MS Office', 'Communication', 'Quick Learner', 'Teamwork'],
        ['Figma', 'Photoshop', 'Illustrator', 'Branding'],
    ];

    /** Cities to assign as preferred work location. */
    public const CITIES = [
        'Houston, TX', 'Dallas, TX', 'New York, NY', 'Chicago, IL',
        'Los Angeles, CA', 'Miami, FL', 'Atlanta, GA', 'Phoenix, AZ',
        'Seattle, WA', 'Denver, CO', 'Boston, MA', 'San Diego, CA',
        'Philadelphia, PA', 'Portland, OR', 'Austin, TX',
    ];

    /** Build the deterministic profile bundle for a single seeker. */
    public static function profileFor(User $user): array
    {
        $idx = abs(crc32((string) $user->id));
        return [
            'headline' => self::HEADLINES[$idx % count(self::HEADLINES)],
            'skills'   => self::SKILL_BANK[$idx % count(self::SKILL_BANK)],
            'city'     => self::CITIES[$idx % count(self::CITIES)],
            'experience_years' => 1 + ($idx % 12),
            'open_to'  => ['Full-time', 'Part-time', 'Contract', 'Remote'][$idx % 4],
        ];
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));

        $query = User::where('role', User::ROLE_JOB_SEEKER)
            ->where('is_active', true);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $seekers = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $stats = Cache::remember('jobSeekersPage.stats', 600, function () {
            return [
                'total_seekers' => User::where('role', User::ROLE_JOB_SEEKER)->where('is_active', true)->count(),
                'open_jobs'     => Job::count(),
                'companies'     => User::where('role', User::ROLE_COMPANY)->count(),
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
