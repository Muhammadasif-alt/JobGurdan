<?php

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
| Employer / company dashboard routes. Required to be inside the auth
| middleware group (defined in web.php). Uses EnsureRole:company.
*/

use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Company\CompanyJobController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

Route::prefix('company')->name('company.')->middleware(EnsureRole::class.':company')->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [CompanyJobController::class, 'profile'])->name('profile');

    // Settings (replaces /user/profile for company)
    Route::get('/settings', [SettingsController::class, 'company'])->name('settings');
    Route::put('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
    Route::put('/settings/company-details', [SettingsController::class, 'updateCompanyDetails'])->name('settings.details');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/photo', [SettingsController::class, 'removePhoto'])->name('settings.photo.remove');

    // Job CRUD scoped to the current company
    Route::get('/jobs', [CompanyJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [CompanyJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [CompanyJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [CompanyJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [CompanyJobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [CompanyJobController::class, 'destroy'])->name('jobs.destroy');
});
