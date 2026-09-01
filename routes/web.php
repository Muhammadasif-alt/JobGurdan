<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Slim loader. Domain-specific routes are split into separate files:
|
|   routes/public.php   — homepage, jobs, blog, SEO landing pages, sitemap
|   routes/admin.php    — admin panel (requires admin role)
|   routes/company.php  — company / employer dashboard
|   routes/seeker.php   — job seeker dashboard
|   routes/ai.php       — AI assistant endpoints
|
| Auth-related routes (login, register, password, etc.) are handled by
| Fortify / Jetstream automatically.
*/

use App\Http\Controllers\Public\ApplicationController;
use App\Http\Controllers\Public\ProfileController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES (No Auth Required)
// ============================================
require __DIR__.'/public.php';

// ============================================
// AUTHENTICATED USER ROUTES
// ============================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Generic /dashboard — dispatch based on the user's role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        return redirect()->route($user->dashboardRouteName());
    })->name('dashboard');

    // Job applications (any authenticated user)
    Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('jobs.apply.form');
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('applications.my');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('public.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('public.profile.update');

    // Save Jobs (toggle save/unsave + list saved)
    Route::post('/jobs/{job}/save', [ProfileController::class, 'saveJob'])->name('jobs.save');
    Route::get('/my-saved-jobs', [ProfileController::class, 'savedJobs'])->name('jobs.saved');

    // Role-specific dashboards split into separate files
    require __DIR__.'/admin.php';
    require __DIR__.'/company.php';
    require __DIR__.'/seeker.php';
    require __DIR__.'/ai.php';
});

// /register is handled by Fortify (resources/views/auth/register.blade.php).
// Fortify already redirects authenticated users via RedirectIfAuthenticated.
