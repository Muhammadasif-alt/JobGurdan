<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\JobSearchService;

class UserJobController extends Controller
{
    /**
     * Display home page with search-focused functionality (Indeed-style).
     * Only provides locations for the search form and basic stats.
     */
    public function index()
    {
        // Use DB::table — returns plain stdClass, ~10× smaller serialized than Eloquent models.
        // Avoids "MySQL server has gone away" when storing in DB cache (max_allowed_packet).
        $locations = Cache::remember('home.locations', 600, function () {
            return DB::table('locations')->select('name')->distinct()->orderBy('name')->get();
        });

        $stats = Cache::remember('home.stats', 600, function () {
            return [
                'total_jobs' => Job::count(),
                'total_categories' => Category::count(),
                'total_locations' => Location::count(),
                'total_companies' => Advertiser::count(),
            ];
        });

        $trendingKeywords = Cache::remember('home.trendingKeywords', 600, function () {
            return Job::select('position')
                ->whereNotNull('position')
                ->where('position', '!=', '')
                ->groupBy('position')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(6)
                ->pluck('position');
        });

        $categories = Cache::remember('home.topCategories', 600, function () {
            return Category::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->take(8)
                ->get(['id', 'name', 'slug']);
        });

        // Latest career-advice blog posts for the homepage section above the FAQ
        $careerPosts = Cache::remember('home.careerPosts', 600, function () {
            return \App\Models\Blog::with('category')
                ->where(function ($q) { $q->where('status', 'published')->orWhereNull('status'); })
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        return view('user.index', [
            'locations' => $locations,
            'stats' => $stats,
            'trendingKeywords' => $trendingKeywords,
            'categories' => $categories,
            'careerPosts' => $careerPosts,
            'featuredJobs' => collect(),
        ]);
    }

    public function showAllJobs(Request $request, JobSearchService $searchService)
    {
        $result = $searchService->search($request, 12);

        // DB::table returns lightweight stdClass — same property access ($loc->name), same
        // Collection methods (->where, ->pluck, ->values) — but a fraction of Eloquent's bytes.
        $uniqueLocations = Cache::remember('jobs.uniqueLocations', 600, function () {
            return DB::table('locations')->select('name')->distinct()->orderBy('name')->get();
        });
        $uniqueAreas = Cache::remember('jobs.uniqueAreas', 600, function () {
            return DB::table('locations')
                ->select('name', 'area')
                ->whereNotNull('area')
                ->orderBy('area')
                ->get();
        });
        $uniquePostalCodes = Cache::remember('jobs.uniquePostalCodes', 600, function () {
            return DB::table('locations')
                ->select('name', 'area', 'postal_code')
                ->whereNotNull('postal_code')
                ->orderBy('postal_code')
                ->get();
        });

        $heroStats = Cache::remember('jobs.heroStats', 600, function () {
            return [
                'total_jobs'      => Job::count(),
                'total_companies' => Advertiser::count(),
                'total_locations' => Location::select('name')->distinct()->count('name'),
            ];
        });
        $topCategories = Cache::remember('jobs.topCategories', 600, function () {
            return Category::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->take(8)
                ->get(['id', 'name', 'slug']);
        });
        $topStates = Cache::remember('jobs.topStates', 600, function () {
            return DB::table('jobs')
                ->join('locations', 'jobs.location_id', '=', 'locations.id')
                ->select('locations.name', DB::raw('COUNT(jobs.id) as job_count'))
                ->groupBy('locations.name')
                ->orderByDesc('job_count')
                ->take(10)
                ->get();
        });

        return view('user.jobs-listings', array_merge($result, [
            'uniqueLocations'   => $uniqueLocations,
            'uniqueAreas'       => $uniqueAreas,
            'uniquePostalCodes' => $uniquePostalCodes,
            'heroStats'         => $heroStats,
            'topCategories'     => $topCategories,
            'topStates'         => $topStates,
        ]));
    }

    public function showJob(Job $job)
    {
        // Eager load only necessary relationships
        $job->load(['category', 'advertiser', 'location']);

        $relatedJobs = $this->getRelatedJobs($job);

        return view('user.job-detail', compact('job', 'relatedJobs'));
    }

    private function getRelatedJobs(Job $job)
    {
        return Job::with(['advertiser', 'location', 'category'])
            ->where('id', '!=', $job->id)
            ->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); })
            ->where(function($q) use ($job) {
                if ($job->category_id) {
                    $q->orWhere('category_id', $job->category_id);
                }
                if ($job->location_id) {
                    $q->orWhere('location_id', $job->location_id);
                }
            })
            ->latest()
            ->take(6)
            ->get();
    }

    /**
     * Resolve a job by a generated slug (position + location) without requiring a slug column.
     * This searches candidate jobs and matches against the runtime-generated slug.
     */
    public function showJobBySlug($slug)
    {
        $slug = trim($slug);

        // Break slug into words to filter candidates
        $words = array_filter(explode('-', $slug));

        // Start with a query that matches any slug word in the position or location
        $query = Job::with(['category', 'advertiser', 'location']);

        foreach ($words as $word) {
            $w = $word;
            $query->where(function($q) use ($w) {
                $q->where('position', 'like', "%{$w}%")
                  ->orWhereHas('location', function($sub) use ($w) {
                      $sub->where('name', 'like', "%{$w}%")->orWhere('area', 'like', "%{$w}%");
                  });
            });
        }

        // Limit candidates to a reasonable number
        $candidates = $query->limit(50)->get();

        // Compare generated slug (standardized format: position-location) from each candidate and return the first exact match
        foreach ($candidates as $job) {
            $generated = \Illuminate\Support\Str::slug($job->position . '-' . ($job->location->name ?? ''));
            if ($generated === $slug) {
                $relatedJobs = $this->getRelatedJobs($job);
                return view('user.job-detail', compact('job', 'relatedJobs'));
            }
        }

        // Not found
        abort(404);
    }

    public function categories(Request $request)
    {
        $categories = Category::withCount(['jobs' => function ($query) {
            $query->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); });
        }])->orderBy('name')->paginate(12);

        // The N+1 below is the major slowdown — already covered by jobs_count above
        // Keep the active_openings property in sync without a query per row
        $categories->each(function ($category) {
            $category->active_openings = $category->jobs_count;
        });

        $heroStats = Cache::remember('categoriesPage.heroStats', 600, function () {
            return [
                'total_jobs'       => Job::count(),
                'total_categories' => Category::count(),
                'total_companies'  => Advertiser::count(),
                'total_locations'  => Location::select('name')->distinct()->count('name'),
            ];
        });

        return view('user.jobs-categories', compact('categories', 'heroStats'));
    }

    public function showByLocation(Request $request, Location $location)
    {
        try {
            // Preserve incoming filters
            $filters = $request->only(['job_type', 'position', 'sort']);

            // Base query for active jobs at this location
            $query = Job::with(['advertiser', 'category'])
                    ->where('location_id', $location->id)
                    ->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); });

            // Filter by job types (supports single or multiple)
            if ($jobTypes = $request->query('job_type')) {
                if (is_array($jobTypes)) {
                    $query->whereIn('job_type', $jobTypes);
                } else {
                    $query->where('job_type', $jobTypes);
                }
            }

            // Simple position search
            if ($position = $request->position) {
                $query->where('position', 'like', "%{$position}%");
            }

            // Sorting
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'position_asc':
                    $query->orderBy('position');
                    break;
                case 'position_desc':
                    $query->orderBy('position', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }

            // Paginate and preserve query string
            $jobs = $query->paginate(10)->appends($filters);

            // Sidebar: job types with counts for this location
            $jobTypesList = Job::selectRaw('job_type, COUNT(*) as count')
                ->where('location_id', $location->id)
                ->whereNotNull('job_type')
                ->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); })
                ->groupBy('job_type')
                ->get();

            // Sidebar: other locations (exclude current) with active job counts
            $otherLocations = Location::withCount(['jobs' => function ($q) use ($location) {
                $q->where(function($q2){ $q2->where('status', 'active')->orWhereNull('status'); })->where('id', '!=', $location->id);
            }])->orderBy('name')->take(15)->get();

            // Sidebar: categories with counts for this location
            $categories = Category::withCount(['jobs' => function ($q) use ($location) {
                $q->where(function($q2){ $q2->where('status', 'active')->orWhereNull('status'); })->where('location_id', $location->id);
            }])->orderBy('name')->get();

            return view('user.location-jobs', compact('jobs', 'location', 'jobTypesList', 'otherLocations', 'categories', 'filters'));
        } catch (\Exception $e) {
            \Log::error('Error in showByLocation: '.$e->getMessage());

            return back()->with('error', 'An error occurred while loading jobs. Please try again.');
        }
    }

    /**
     * Display all locations with job counts.
     */
    public function locations()
    {
        $locations = Location::withCount(['jobs' => function ($query) {
            $query->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); }); // Count only active or unspecified jobs
        }])->orderBy('name')->paginate(12); // 12 locations per page

        $defaultImage = 'user/images/popular-location-02.jpg';

        $heroStats = Cache::remember('locationsPage.heroStats', 600, function () {
            return [
                'total_jobs'      => Job::count(),
                'total_locations' => Location::count(),
                'total_states'    => Location::select('name')->distinct()->count('name'),
                'total_companies' => Advertiser::count(),
            ];
        });

        $topStates = Cache::remember('locationsPage.topStates', 600, function () {
            return DB::table('jobs')
                ->join('locations', 'jobs.location_id', '=', 'locations.id')
                ->select('locations.name', DB::raw('COUNT(jobs.id) as job_count'))
                ->groupBy('locations.name')
                ->orderByDesc('job_count')
                ->take(6)
                ->get();
        });

        return view('user.locations', compact('locations', 'defaultImage', 'heroStats', 'topStates'));
    }

    public function showCategory($categorySlug)
    {
        // Get the category with the given slug
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        // Get active jobs in this category with pagination
        $jobs = Job::with(['advertiser', 'location'])
            ->where('category_id', $category->id)
            ->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); })
            ->latest()
            ->paginate(9);

        // Get distinct locations for the filter
        $locations = Location::select('name')
            ->distinct('name')
            ->orderBy('name')
            ->get();

        // Get all categories with job counts for the sidebar
        $categories = Category::withCount(['jobs' => function ($query) {
            $query->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); });
        }])
            ->orderBy('name')
            ->get();

        return view('user.category-jobs', [
            'jobs' => $jobs,
            'category' => $category,
            'categories' => $categories,
            'locations' => $locations,
        ]);
    }

    public function companies(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $sort   = $request->input('sort', 'name');

        $query = Advertiser::withCount(['jobs' => function ($q) {
            $q->where(function($qq){ $qq->where('status', 'active')->orWhereNull('status'); });
        }]);

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        switch ($sort) {
            case 'jobs':
                $query->orderByDesc('jobs_count')->orderBy('name');
                break;
            case 'newest':
                $query->orderByDesc('id');
                break;
            default:
                $query->orderBy('name');
        }

        $companies = $query->paginate(12)->withQueryString();

        $stats = [
            'total_companies' => Advertiser::count(),
            'total_jobs'      => Job::where(function($q){ $q->where('status', 'active')->orWhereNull('status'); })->count(),
            'hiring_now'      => Advertiser::whereHas('jobs', function ($q) {
                                    $q->where(function($qq){ $qq->where('status', 'active')->orWhereNull('status'); });
                                })->count(),
        ];

        return view('user.companies', compact('companies', 'stats', 'search', 'sort'));
    }

    /**
     * Display jobs for a specific company/advertiser.
     */
    public function showCompany(Advertiser $advertiser)
    {
        // Get active jobs for this company with pagination
        $jobs = Job::with(['category', 'location'])
            ->where('advertiser_id', $advertiser->id)
            ->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); })
            ->latest()
            ->paginate(10);

        // Get unique locations for the filter
        $locations = Location::select('name')
            ->distinct('name')
            ->orderBy('name')
            ->get();

        // Get all categories with job counts for the sidebar
        $categories = Category::withCount(['jobs' => function ($query) {
            $query->where(function($q){ $q->where('status', 'active')->orWhereNull('status'); });
        }])
            ->orderBy('name')
            ->get();

        return view('user.companies-jobs', [
            'jobs' => $jobs,
            'company' => $advertiser,
            'categories' => $categories,
            'locations' => $locations,
        ]);
    }

    public function about_us()
    {
        return view('user.about-us');
    }

    public function contact_us()
    {
        return view('user.contact-us');
    }

    public function search(Request $request, JobSearchService $searchService)
    {
        $result = $searchService->search($request, 10);

        return view('user.search-results', array_merge($result, [
            'selectedLocation' => $request->location,
            'keywords' => $request->input('keywords') ?? $request->input('position'),
        ]));
    }
}
