<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All admin panel routes. Required to be inside the auth middleware group
| (defined in web.php). Uses the EnsureRole:admin middleware to restrict
| access to admin users only.
*/

use App\Http\Controllers\Admin\AdvertiserController;
use App\Http\Controllers\Admin\BlogCatgoriesController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\JobImportExportController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

// Admin Dashboard
Route::get('/administration', [JobController::class, 'dashboard'])
    ->middleware(EnsureRole::class.':admin')
    ->name('admin.dashboard');

// Admin Resource Routes — admins ONLY
Route::prefix('admin')->name('admin.')->middleware(EnsureRole::class.':admin')->group(function () {
    // Shortcut: redirect /admin to the admin dashboard
    Route::get('/', fn () => redirect()->route('admin.dashboard'));

    // Jobs Management
    Route::resource('jobs', JobController::class);
    Route::get('/jobs/import/form', [JobImportExportController::class, 'showImportForm'])->name('jobs.import.form');
    Route::post('/jobs/import', [JobImportExportController::class, 'import'])->name('jobs.import');
    Route::get('/jobs/export', [JobImportExportController::class, 'export'])->name('jobs.export');

    // CRUD resources
    Route::resource('advertisers', AdvertiserController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('blogcategories', BlogCatgoriesController::class);
    Route::resource('blogs', BlogController::class);

    // Users Management (custom delete-photo + standard CRUD)
    Route::delete('users/{user}/photo', [UserController::class, 'removePhoto'])->name('users.photo.remove');
    Route::resource('users', UserController::class);

    // Contact Messages
    Route::post('contact-messages/bulk-destroy', [ContactMessageController::class, 'bulkDestroy'])->name('contact-messages.bulk-destroy');
    Route::post('contact-messages/scan-spam', [ContactMessageController::class, 'scanSpam'])->name('contact-messages.scan-spam');
    Route::resource('contact-messages', ContactMessageController::class)->except('create', 'edit');

    // Dangerous: delete all data
    Route::post('/cleanup', [JobController::class, 'destroyAll'])->name('cleanup');
});
