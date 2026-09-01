<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class CompanyDashboardController extends Controller
{
    /**
     * Company dashboard — overview of the employer's job posts and activity.
     *
     * NOTE: This deliberately keeps the data shape minimal. We don't yet have a
     *       direct user_id column on jobs; once that link is added the queries
     *       below can be filtered to "this company's jobs" only.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Show only THIS company's own jobs.
        $stats = [
            'total_jobs'  => Job::where('user_id', $user->id)->count(),
            'active_jobs' => Job::where('user_id', $user->id)
                                ->where(fn($q) => $q->where('status', 'active')->orWhereNull('status'))
                                ->count(),
            'this_week'   => Job::where('user_id', $user->id)
                                ->where('created_at', '>=', now()->subWeek())
                                ->count(),
        ];

        $recentJobs = Job::with(['advertiser', 'category', 'location'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        return view('company.dashboard', compact('user', 'stats', 'recentJobs'));
    }
}
