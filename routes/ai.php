<?php

/*
|--------------------------------------------------------------------------
| AI Assistant Routes
|--------------------------------------------------------------------------
| Shared AI helpers across admin / company / seeker panels.
| Must be inside the auth middleware group (defined in web.php).
*/

use App\Http\Controllers\AiAssistantController;
use Illuminate\Support\Facades\Route;

Route::prefix('ai')->name('ai.')->group(function () {
    Route::post('/job-description', [AiAssistantController::class, 'jobDescription'])->name('job-description');
    Route::post('/polish-bio', [AiAssistantController::class, 'polishBio'])->name('polish-bio');
    Route::post('/polish-headline', [AiAssistantController::class, 'polishHeadline'])->name('polish-headline');
    Route::post('/extract-skills', [AiAssistantController::class, 'extractSkills'])->name('extract-skills');
    Route::post('/category-description', [AiAssistantController::class, 'categoryDescription'])->name('category-description');
    Route::post('/blog-content', [AiAssistantController::class, 'blogContent'])->name('blog-content');
    Route::post('/blog-excerpt', [AiAssistantController::class, 'blogExcerpt'])->name('blog-excerpt');
    Route::post('/blog-meta', [AiAssistantController::class, 'blogMeta'])->name('blog-meta');
    Route::post('/generic', [AiAssistantController::class, 'generic'])->name('generic');
});
