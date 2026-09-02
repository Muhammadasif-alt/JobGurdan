<?php

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
| Homepage, jobs listing, categories, locations, companies, blog,
| SEO landing pages, and sitemap.xml.
*/

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Public\BlogController as PublicBlogController;
use App\Http\Controllers\Public\JobSeekerPublicController;
use App\Http\Controllers\Site\UserJobController;
use Illuminate\Support\Facades\Route;

// Homepage & main job pages
Route::get('/', [UserJobController::class, 'index'])->name('home');
Route::get('/jobs', [UserJobController::class, 'showAllJobs'])->name('jobs.index');

// Autocomplete API for the search box (returns JSON suggestions)
Route::get('/api/jobs/autocomplete', [UserJobController::class, 'autocomplete'])->name('jobs.autocomplete');

// Redirect legacy id-prefixed job URLs (/jobs/12345-some-title) to slug-only canonical URL (301)
Route::get('/jobs/{id}-{any}', function ($id, $any) {
    $job = \App\Models\Job::with('location')->find($id);
    if (! $job) {
        abort(404);
    }
    $slug = \Illuminate\Support\Str::slug($job->position.'-'.($job->location->name ?? ''));

    return redirect()->route('jobs.show', $slug, 301);
})->where('id', '[0-9]+')->where('any', '.*');

Route::get('/categories/{category:slug}', [UserJobController::class, 'showCategory'])->name('jobs.category');
Route::get('/search', [UserJobController::class, 'search'])->name('jobs.search');
Route::get('/locations', [UserJobController::class, 'locations'])->name('jobs.locations');
Route::get('/location/{location}', [UserJobController::class, 'showByLocation'])->name('jobs.location');
Route::get('/categories', [UserJobController::class, 'categories'])->name('jobs.categories');
Route::get('/companies', [UserJobController::class, 'companies'])->name('jobs.companies');
Route::get('/companies/{advertiser}', [UserJobController::class, 'showCompany'])->name('companies.show');

// Public job seekers directory
Route::get('/job-seekers', [JobSeekerPublicController::class, 'index'])->name('job-seekers.index');
Route::get('/job-seekers/{username}', [JobSeekerPublicController::class, 'show'])
    ->where('username', '[A-Za-z0-9_.]+')
    ->name('job-seekers.show');

// Static informational pages from UserJobController
Route::get('/about-us', [UserJobController::class, 'about_us'])->name('about.us');
Route::get('/contact-us', [UserJobController::class, 'contact_us'])->name('contact.us');
Route::post('/contact-us', [ContactMessageController::class, 'store'])->name('contact.store');

// /jobs/search → redirect to canonical /search (named jobs.search)
Route::get('/jobs/search', fn () => redirect()->route('jobs.search', request()->query(), 301));

// Job detail by SEO slug (no ID). Placed after specific /jobs routes to avoid capturing reserved paths.
Route::get('/jobs/{slug}', [UserJobController::class, 'showJobBySlug'])
    ->name('jobs.show')
    ->where('slug', '(?!search$)[A-Za-z0-9\-]+');

// Blog routes (public)
Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [PublicBlogController::class, 'show'])->name('blog.show');

// ============================================
// SEO Landing Pages — States
// ============================================
Route::view('/jobs-in-texas', 'pages.jobs-in-texas')->name('pages.jobs-in-texas');
Route::view('/jobs-in-california', 'pages.jobs-in-california')->name('pages.jobs-in-california');
Route::view('/jobs-in-new-york', 'pages.jobs-in-new-york')->name('pages.jobs-in-new-york');
Route::view('/jobs-in-florida', 'pages.jobs-in-florida')->name('pages.jobs-in-florida');
Route::view('/jobs-in-illinois', 'pages.jobs-in-illinois')->name('pages.jobs-in-illinois');
Route::view('/jobs-in-pennsylvania', 'pages.jobs-in-pennsylvania')->name('pages.jobs-in-pennsylvania');
Route::view('/jobs-in-ohio', 'pages.jobs-in-ohio')->name('pages.jobs-in-ohio');
Route::view('/jobs-in-georgia', 'pages.jobs-in-georgia')->name('pages.jobs-in-georgia');
Route::view('/jobs-in-north-carolina', 'pages.jobs-in-north-carolina')->name('pages.jobs-in-north-carolina');
Route::view('/jobs-in-michigan', 'pages.jobs-in-michigan')->name('pages.jobs-in-michigan');
Route::view('/jobs-in-new-jersey', 'pages.jobs-in-new-jersey')->name('pages.jobs-in-new-jersey');
Route::view('/jobs-in-virginia', 'pages.jobs-in-virginia')->name('pages.jobs-in-virginia');
Route::view('/jobs-in-washington', 'pages.jobs-in-washington')->name('pages.jobs-in-washington');
Route::view('/jobs-in-arizona', 'pages.jobs-in-arizona')->name('pages.jobs-in-arizona');
Route::view('/jobs-in-massachusetts', 'pages.jobs-in-massachusetts')->name('pages.jobs-in-massachusetts');

// ============================================
// SEO Landing Pages — Industries
// ============================================
Route::view('/warehouse-jobs', 'pages.warehouse-jobs')->name('pages.warehouse-jobs');
Route::view('/healthcare-jobs', 'pages.healthcare-jobs')->name('pages.healthcare-jobs');
Route::view('/truck-driver-jobs', 'pages.truck-driver-jobs')->name('pages.truck-driver-jobs');
Route::view('/construction-jobs', 'pages.construction-jobs')->name('pages.construction-jobs');
Route::view('/it-jobs', 'pages.it-jobs')->name('pages.it-jobs');
Route::view('/software-developer-jobs', 'pages.software-developer-jobs')->name('pages.software-developer-jobs');
Route::view('/data-entry-jobs', 'pages.data-entry-jobs')->name('pages.data-entry-jobs');
Route::view('/customer-service-jobs', 'pages.customer-service-jobs')->name('pages.customer-service-jobs');
Route::view('/marketing-jobs', 'pages.marketing-jobs')->name('pages.marketing-jobs');
Route::view('/accounting-jobs', 'pages.accounting-jobs')->name('pages.accounting-jobs');
Route::view('/retail-jobs', 'pages.retail-jobs')->name('pages.retail-jobs');
Route::view('/security-guard-jobs', 'pages.security-guard-jobs')->name('pages.security-guard-jobs');

// ============================================
// SEO Landing Pages — Remote
// ============================================
Route::view('/remote-jobs-usa', 'pages.remote-jobs-usa')->name('pages.remote-jobs-usa');
Route::view('/work-from-home-jobs', 'pages.work-from-home-jobs')->name('pages.work-from-home-jobs');
Route::view('/online-jobs-usa', 'pages.online-jobs-usa')->name('pages.online-jobs-usa');
Route::view('/part-time-remote-jobs', 'pages.part-time-remote-jobs')->name('pages.part-time-remote-jobs');
Route::view('/entry-level-remote-jobs', 'pages.entry-level-remote-jobs')->name('pages.entry-level-remote-jobs');

// ============================================
// SEO Landing Pages — Experience Level
// ============================================
Route::view('/entry-level-jobs', 'pages.entry-level-jobs')->name('pages.entry-level-jobs');
Route::view('/no-experience-jobs', 'pages.no-experience-jobs')->name('pages.no-experience-jobs');
Route::view('/graduate-jobs', 'pages.graduate-jobs')->name('pages.graduate-jobs');
Route::view('/internship-jobs', 'pages.internship-jobs')->name('pages.internship-jobs');

// ============================================
// Static informational pages
// ============================================
Route::view('/privacy-policy', 'pages.privacy')->name('pages.privacy');
Route::view('/terms-of-service', 'pages.terms')->name('pages.terms');
Route::view('/contact', 'pages.contact')->name('pages.contact');
Route::view('/disclaimer', 'pages.disclaimer')->name('pages.disclaimer');

// ============================================
// HTTP-triggered scheduler (fallback for hosts where shell cron is unreliable)
// Hit via cron: wget -q -O- "https://jobgader.com/cron/run?token=YOUR_SECRET"
// ============================================
Route::get('/cron/run', function (\Illuminate\Http\Request $request) {
    if ($request->query('token') !== config('app.cron_token', 'change-me-jobs-cron-2026')) {
        abort(403);
    }
    \Illuminate\Support\Facades\Artisan::call('schedule:run');

    return response(\Illuminate\Support\Facades\Artisan::output(), 200)
        ->header('Content-Type', 'text/plain');
});

// ============================================
// Sitemap (XML) — split into a sitemap-index + chunked child sitemaps.
// Single-file sitemaps over 50,000 URLs / 50 MB are rejected by Google
// and flagged by Semrush. With 55k+ jobs the old one-file approach burst that ceiling.
// ============================================

/** Build a single <url> entry. */
$sitemapUrl = function (string $loc, string $changefreq = 'weekly', string $priority = '0.6', ?string $lastmod = null): string {
    $lastmod = $lastmod ?? now()->toDateString();

    return "  <url>\n"
         .'    <loc>'.htmlspecialchars($loc, ENT_XML1)."</loc>\n"
         ."    <lastmod>{$lastmod}</lastmod>\n"
         ."    <changefreq>{$changefreq}</changefreq>\n"
         ."    <priority>{$priority}</priority>\n"
         ."  </url>\n";
};

/** Wrap a <url> stream into a complete <urlset> response. */
$sitemapResponse = function (string $inner): \Illuminate\Http\Response {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
         .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
         .$inner
         .'</urlset>';

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('Cache-Control', 'public, max-age=3600');
};

/** Cap a single child sitemap at 45k URLs (under Google's 50k hard limit). */
$jobsPerSitemap = 45000;

// /sitemap.xml — index pointing at child sitemaps. This is the URL Google fetches first.
Route::get('/sitemap.xml', function () use ($jobsPerSitemap) {
    $today = now()->toDateString();
    $jobCount = 0;
    try {
        $jobCount = (int) \App\Models\Job::query()->count();
    } catch (\Throwable $e) {
    }
    $jobChunks = max(1, (int) ceil($jobCount / $jobsPerSitemap));

    $entries = ['core', 'categories', 'locations', 'companies', 'blog'];
    for ($i = 1; $i <= $jobChunks; $i++) {
        $entries[] = "jobs-{$i}";
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
         .'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
    foreach ($entries as $name) {
        $xml .= "  <sitemap>\n"
              .'    <loc>'.htmlspecialchars(url("/sitemap-{$name}.xml"), ENT_XML1)."</loc>\n"
              ."    <lastmod>{$today}</lastmod>\n"
              ."  </sitemap>\n";
    }
    $xml .= '</sitemapindex>';

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('Cache-Control', 'public, max-age=3600');
});

// Core + static info + state/industry SEO landing pages.
Route::get('/sitemap-core.xml', function () use ($sitemapUrl, $sitemapResponse) {
    $inner = '';
    $inner .= $sitemapUrl(url('/'), 'daily', '1.0');
    $inner .= $sitemapUrl(url('/jobs'), 'hourly', '0.9');
    $inner .= $sitemapUrl(url('/companies'), 'daily', '0.8');
    $inner .= $sitemapUrl(url('/job-seekers'), 'daily', '0.7');
    $inner .= $sitemapUrl(url('/categories'), 'weekly', '0.7');
    $inner .= $sitemapUrl(url('/locations'), 'weekly', '0.7');
    $inner .= $sitemapUrl(url('/blog'), 'weekly', '0.7');

    foreach (['/about-us', '/contact-us', '/privacy-policy', '/terms-of-service', '/disclaimer'] as $p) {
        $inner .= $sitemapUrl(url($p), 'monthly', '0.4');
    }

    $states = ['texas', 'california', 'new-york', 'florida', 'illinois', 'pennsylvania', 'ohio', 'georgia',
        'north-carolina', 'michigan', 'new-jersey', 'virginia', 'washington', 'arizona', 'massachusetts'];
    foreach ($states as $s) {
        $inner .= $sitemapUrl(url('/jobs-in-'.$s), 'weekly', '0.7');
    }

    $industries = ['warehouse-jobs', 'healthcare-jobs', 'truck-driver-jobs', 'customer-service-jobs',
        'marketing-jobs', 'accounting-jobs', 'retail-jobs', 'security-guard-jobs',
        'remote-jobs-usa', 'work-from-home-jobs', 'online-jobs-usa',
        'part-time-remote-jobs', 'entry-level-remote-jobs',
        'entry-level-jobs', 'no-experience-jobs', 'graduate-jobs', 'internship-jobs'];
    foreach ($industries as $i) {
        $inner .= $sitemapUrl(url('/'.$i), 'weekly', '0.7');
    }

    return $sitemapResponse($inner);
});

Route::get('/sitemap-categories.xml', function () use ($sitemapUrl, $sitemapResponse) {
    $inner = '';
    try {
        foreach (\App\Models\Categories::query()->whereNotNull('slug')->get(['slug', 'updated_at']) as $cat) {
            $inner .= $sitemapUrl(url('/categories/'.$cat->slug), 'weekly', '0.6',
                optional($cat->updated_at)->toDateString());
        }
    } catch (\Throwable $e) {
    }

    return $sitemapResponse($inner);
});

Route::get('/sitemap-locations.xml', function () use ($sitemapUrl, $sitemapResponse) {
    $inner = '';
    try {
        foreach (\App\Models\Location::query()->get(['id', 'updated_at']) as $loc) {
            $inner .= $sitemapUrl(url('/location/'.$loc->id), 'weekly', '0.6',
                optional($loc->updated_at)->toDateString());
        }
    } catch (\Throwable $e) {
    }

    return $sitemapResponse($inner);
});

Route::get('/sitemap-companies.xml', function () use ($sitemapUrl, $sitemapResponse) {
    $inner = '';
    try {
        foreach (\App\Models\Advertiser::query()->get(['id', 'updated_at']) as $c) {
            $inner .= $sitemapUrl(url('/companies/'.$c->id), 'weekly', '0.5',
                optional($c->updated_at)->toDateString());
        }
    } catch (\Throwable $e) {
    }

    return $sitemapResponse($inner);
});

Route::get('/sitemap-blog.xml', function () use ($sitemapUrl, $sitemapResponse) {
    $inner = '';
    try {
        foreach (\App\Models\Blog::query()->whereNotNull('slug')->get(['slug', 'updated_at']) as $b) {
            $inner .= $sitemapUrl(url('/blog/'.$b->slug), 'monthly', '0.5',
                optional($b->updated_at)->toDateString());
        }
    } catch (\Throwable $e) {
    }

    return $sitemapResponse($inner);
});

// /sitemap-jobs-{n}.xml — 45k jobs per chunk, paged by id desc.
// LEFT JOIN locations in one query + cursor() streams rows from MySQL,
// so peak memory stays flat even at 45k rows and there are no N+1 lookups.
Route::get('/sitemap-jobs-{chunk}.xml', function (int $chunk) use ($sitemapUrl, $sitemapResponse, $jobsPerSitemap) {
    abort_if($chunk < 1, 404);
    $inner = '';
    try {
        \DB::table('jobs')
            ->leftJoin('locations', 'jobs.location_id', '=', 'locations.id')
            ->select('jobs.id', 'jobs.position', 'locations.name as location_name', 'jobs.updated_at')
            ->orderBy('jobs.id', 'desc')
            ->skip(($chunk - 1) * $jobsPerSitemap)
            ->take($jobsPerSitemap)
            ->cursor()
            ->each(function ($job) use (&$inner, $sitemapUrl) {
                $slug = \Illuminate\Support\Str::slug($job->position.'-'.($job->location_name ?? ''));
                if ($slug === '') {
                    return;
                }
                $inner .= $sitemapUrl(url('/jobs/'.$slug), 'daily', '0.8',
                    $job->updated_at ? substr((string) $job->updated_at, 0, 10) : null);
            });
    } catch (\Throwable $e) {
    }

    if ($inner === '') {
        abort(404);
    }

    return $sitemapResponse($inner);
})->where('chunk', '[0-9]+');
