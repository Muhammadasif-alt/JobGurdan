<?php

/*
|--------------------------------------------------------------------------
| Job Seeker Routes
|--------------------------------------------------------------------------
| Candidate dashboard routes. Required to be inside the auth middleware
| group (defined in web.php). Uses EnsureRole:job_seeker.
*/

use App\Http\Controllers\Seeker\JobAlertController;
use App\Http\Controllers\Seeker\JobSeekerDashboardController;
use App\Http\Controllers\Seeker\SeekerProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

Route::prefix('seeker')->name('seeker.')->middleware(EnsureRole::class.':job_seeker')->group(function () {
    Route::get('/dashboard', [JobSeekerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/ai-matches', [JobSeekerDashboardController::class, 'aiMatches'])->name('dashboard.ai-matches');
    Route::get('/recommended', [JobSeekerDashboardController::class, 'recommended'])->name('recommended');

    // Profile
    Route::get('/profile', [SeekerProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [SeekerProfileController::class, 'update'])->name('profile.update');

    // Resume / CV
    Route::get('/resume', [SeekerProfileController::class, 'resume'])->name('resume');
    Route::post('/resume', [SeekerProfileController::class, 'uploadResume'])->name('resume.upload');
    Route::delete('/resume', [SeekerProfileController::class, 'deleteResume'])->name('resume.delete');

    // Edit auto-extracted resume content (fix wrong fetches)
    Route::put('/resume-data', [SeekerProfileController::class, 'updateResumeData'])->name('resume-data.update');

    // My Applications
    Route::get('/applications', [SeekerProfileController::class, 'applications'])->name('applications');

    // Settings (replaces /user/profile for seeker)
    Route::get('/settings', [SettingsController::class, 'seeker'])->name('settings');
    Route::put('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/photo', [SettingsController::class, 'removePhoto'])->name('settings.photo.remove');

    // Job alerts — subscriptions to receive new matching jobs by email
    Route::resource('job-alerts', JobAlertController::class)->except(['show']);
    Route::post('job-alerts/{jobAlert}/toggle', [JobAlertController::class, 'toggle'])->name('job-alerts.toggle');
});
