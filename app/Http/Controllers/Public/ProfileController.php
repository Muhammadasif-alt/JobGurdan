<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        // Delegate to Jetstream profile or show a placeholder
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('profile.show');
    }

    public function update(Request $request)
    {
        // Stub - Jetstream handles profile updates in the packaged controllers
        return back()->with('success', 'Profile updated (stub).');
    }

    public function saveJob(Request $request)
    {
        // Stub: toggle a saved job list in session as placeholder behavior
        $jobId = $request->route('job') ?? $request->input('job_id');
        $saved = session()->get('saved_jobs', []);

        if (in_array($jobId, $saved)) {
            $saved = array_diff($saved, [$jobId]);
            session()->put('saved_jobs', $saved);

            return back()->with('success', 'Job removed from saved list.');
        }

        $saved[] = $jobId;
        session()->put('saved_jobs', $saved);

        return back()->with('success', 'Job saved.');
    }
}
