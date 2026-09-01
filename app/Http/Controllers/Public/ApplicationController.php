<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function create(Job $job)
    {
        // Minimal stub: require login, otherwise redirect to job detail
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // In a full implementation, show application form view
        return redirect()->route('jobs.show', \Illuminate\Support\Str::slug($job->position . ' ' . ($job->location->name ?? '')))->with('success', 'Application form (stub)');
    }

    public function store(Request $request, Job $job)
    {
        // Minimal stub: Accept application and redirect back
        // TODO: Implement real application storage
        return redirect()->route('jobs.show', \Illuminate\Support\Str::slug($job->position . ' ' . ($job->location->name ?? '')))->with('success', 'Application submitted (stub).');
    }

    public function myApplications()
    {
        // Minimal stub: show a placeholder page
        return view('user.applications', ['applications' => []]);
    }
}
