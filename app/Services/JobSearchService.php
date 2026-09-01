<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class JobSearchService
{
    /**
     * Build and execute job search query with filters and pagination.
     * Returns an array with 'jobs', 'locations', and 'filters'.
     */
    public function search(Request $request, $perPage = 10)
    {
        $filters = $request->only([
            'keywords', 'position', 'location', 'area', 'postal_code', 'sort',
            'category', 'job_type', 'salary_min', 'salary_max', 'experience_level', 'per_page'
        ]);

        $query = Job::with(['advertiser', 'category', 'location'])->active();

        // Keywords (support multiple terms)
        $keywords = $request->input('keywords') ?? $request->input('position') ?? null;
        if ($keywords) {
            $query->keyword($keywords);
        }

        // Location handling — supports state, area, and ZIP code filters together
        $location = $request->input('location');
        $area     = $request->input('area');
        $postal   = $request->input('postal_code');

        if ($location || $area || $postal) {
            $query->whereHas('location', function ($q) use ($location, $area, $postal) {
                if ($location) {
                    if (is_numeric($location)) {
                        $q->where('locations.id', $location);
                    } else {
                        $q->where('name', $location);
                    }
                }
                if ($area) {
                    $q->where('area', $area);
                }
                if ($postal) {
                    $q->where('postal_code', $postal);
                }
            });
        }

        // Category
        if ($category = $request->input('category')) {
            $query->filterCategory($category);
        }

        // Job type
        if ($jobType = $request->input('job_type')) {
            $query->filterJobType($jobType);
        }

        // Salary range
        $min = $request->input('salary_min');
        $max = $request->input('salary_max');
        $query->salaryRange($min, $max);

        // Experience level
        if ($exp = $request->input('experience_level')) {
            $query->experienceLevel($exp);
        }

        // Sorting: allow newest, oldest, relevance, position_asc/desc
        $sort = $request->input('sort');
        if ($sort) {
            switch ($sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'position_asc':
                    $query->orderBy('position');
                    break;
                case 'position_desc':
                    $query->orderBy('position', 'desc');
                    break;
                case 'relevance':
                    // Basic relevance: prioritize position matches
                    if ($keywords) {
                        $like = "%{$keywords}%";
                        $query->orderByRaw("CASE WHEN position LIKE ? THEN 0 ELSE 1 END", [$like])->latest();
                    } else {
                        $query->latest();
                    }
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            // If keywords provided, boost relevance; otherwise latest
            if ($keywords) {
                $like = "%{$keywords}%";
                $query->orderByRaw("CASE WHEN position LIKE ? THEN 0 ELSE 1 END", [$like])->latest();
            } else {
                $query->latest();
            }
        }

        $perPage = intval($request->input('per_page', $perPage));
        $jobs = $query->paginate($perPage)->appends($filters);
        $locations = Location::orderBy('name')->get();

        return [
            'jobs' => $jobs,
            'locations' => $locations,
            'filters' => $filters,
        ];
    }
}
