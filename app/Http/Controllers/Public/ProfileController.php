<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        // Delegate to Jetstream profile or show a placeholder
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        return view('profile.show');
    }

    public function update(Request $request)
    {
        // Stub - Jetstream handles profile updates in the packaged controllers
        return back()->with('success', 'Profile updated (stub).');
    }

    /**
     * Toggle save/unsave a job for the authenticated user (DB-backed).
     * Returns JSON for AJAX or redirects back for traditional form POST.
     */
    public function saveJob(Request $request, Job $job)
    {
        $user = $request->user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['error' => 'auth_required', 'login_url' => route('login')], 401)
                : redirect()->route('login')->with('info', 'Sign in to save jobs.');
        }

        $existing = $user->savedJobs()->where('jobs.id', $job->id)->exists();

        if ($existing) {
            $user->savedJobs()->detach($job->id);
            $saved = false;
            $message = 'Job removed from saved list.';
        } else {
            $user->savedJobs()->attach($job->id);
            $saved = true;
            $message = 'Job saved.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'saved' => $saved,
                'message' => $message,
                'count' => $user->savedJobs()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * List jobs the authenticated user has saved.
     */
    public function savedJobs(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('info', 'Sign in to view your saved jobs.');
        }

        $jobs = $user->savedJobs()
            ->with(['advertiser', 'location', 'category'])
            ->paginate(15);

        return view('user.saved-jobs', compact('jobs'));
    }
}
