<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Display dashboard with statistics and charts.
     */
    public function dashboard()
    {
        try {
            // Simple counts only - fastest
            $stats = [
                'total_jobs' => Job::count(),
                'total_categories' => Category::count(),
                'total_advertisers' => Advertiser::count(),
                'total_locations' => Location::count(),
            ];

            // Get recent jobs - no relationships
            $recent_jobs = Job::latest()->limit(10)->get();

            return view('admin.index', compact('stats', 'recent_jobs')); // Updated view path
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: '.$e->getMessage());

            return view('admin.index', [ // Updated view path
                'stats' => ['total_jobs' => 0, 'total_categories' => 0, 'total_advertisers' => 0, 'total_locations' => 0],
                'recent_jobs' => [],
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // eager-load relations to avoid N+1 queries; show newest jobs first
        $jobs = Job::with(['category', 'advertiser', 'location'])
            ->latest('id')
            ->paginate(15);

        return view('admin.jobs.index', compact('jobs')); // Updated view path
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $advertisers = Advertiser::all();
        $locations = Location::all();

        return view('admin.jobs.create', compact('categories', 'advertisers', 'locations')); // Updated view path
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'advertiser_id' => 'nullable|exists:advertisers,id',
            'location_id' => 'nullable|exists:locations,id',
            'position' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'work_hours' => 'nullable|string|max:255',
            'salary_currency' => 'nullable|string|max:10',
            'salary_period' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:255',
            'sell_price' => 'nullable|numeric|min:0',
            'sell_price_currency' => 'nullable|string|max:10',
            'revenue_type' => 'nullable|string|max:255',
            'salary_minimum' => 'nullable|numeric|min:0',
            'salary_maximum' => 'nullable|numeric|min:0',
            'application_url' => 'nullable|url',
        ]);

        // Duplicate guard: same position + employer + location already exists?
        $hash = Job::makeDedupeHash(
            $validated['position'] ?? null,
            $validated['advertiser_id'] ?? null,
            $validated['location_id'] ?? null
        );
        if ($hash && Job::where('dedupe_hash', $hash)->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['position' => 'A job with the same position, employer and location already exists.']);
        }

        Job::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        $job->load(['category', 'advertiser', 'location']);

        return view('admin.jobs.show', compact('job')); // Updated view path
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        $categories = Category::all();
        $advertisers = Advertiser::all();
        $locations = Location::all();

        return view('admin.jobs.edit', compact('job', 'categories', 'advertisers', 'locations')); // Updated view path
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'advertiser_id' => 'nullable|exists:advertisers,id',
            'location_id' => 'nullable|exists:locations,id',
            'position' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'work_hours' => 'nullable|string|max:255',
            'salary_currency' => 'nullable|string|max:10',
            'salary_period' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:255',
            'sell_price' => 'nullable|numeric|min:0',
            'sell_price_currency' => 'nullable|string|max:10',
            'revenue_type' => 'nullable|string|max:255',
            'salary_minimum' => 'nullable|numeric|min:0',
            'salary_maximum' => 'nullable|numeric|min:0',
            'application_url' => 'nullable|url',
        ]);

        // Duplicate guard â€” exclude the current job from the check
        $hash = Job::makeDedupeHash(
            $validated['position'] ?? null,
            $validated['advertiser_id'] ?? null,
            $validated['location_id'] ?? null
        );
        if ($hash && Job::where('dedupe_hash', $hash)->where('id', '!=', $job->id)->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['position' => 'Another job with the same position, employer and location already exists.']);
        }

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    /**
     * Permanently delete all jobs, categories, advertisers and locations.
     * Requires a confirmation string 'DELETE' to proceed.
     */
    public function destroyAll(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DELETE',
        ]);

        try {
            DB::transaction(function () {
                // Delete jobs first to avoid FK issues, then related tables.
                Job::query()->delete();
                Category::query()->delete();
                Advertiser::query()->delete();
                Location::query()->delete();
            });

            return redirect()->route('admin.dashboard')->with('success', 'All jobs, categories, advertisers and locations have been deleted.');
        } catch (\Exception $e) {
            \Log::error('Cleanup Error: '.$e->getMessage());

            return redirect()->route('admin.dashboard')->with('error', 'An error occurred while deleting records.');
        }
    }

    public function showImportForm()
    {
        return view('admin.jobs.import'); // Updated view path
    }
}
