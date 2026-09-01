<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Central cache flusher for the public site.
 * Called automatically by Job/Category/Location/Advertiser model events
 * so admin edits show up instantly without waiting for the 10-min TTL.
 */
class SiteCache
{
    /** Static cache keys used in the public-site controllers. */
    public const KEYS = [
        // Homepage
        'home.locations',
        'home.stats',
        'home.trendingKeywords',
        'home.topCategories',
        'home.careerPosts',
        // Jobs listing page
        'jobs.uniqueLocations',
        'jobs.uniqueAreas',
        'jobs.uniquePostalCodes',
        'jobs.heroStats',
        'jobs.topCategories',
        'jobs.topStates',
        // Categories page
        'categoriesPage.heroStats',
        // Locations page
        'locationsPage.heroStats',
        'locationsPage.topStates',
    ];

    /** Flush every public-site cache and bump the SEO landing partial version. */
    public static function flush(): void
    {
        foreach (self::KEYS as $key) {
            Cache::forget($key);
        }

        // The SEO landing partial uses dynamic keys (md5 of the filter combo),
        // so we bump a "version" prefix instead — any old keys are ignored.
        $current = (int) Cache::get('seoLanding:version', 0);
        Cache::forever('seoLanding:version', $current + 1);
    }

    /** Current SEO-landing cache version (used in the partial). */
    public static function seoVersion(): int
    {
        return (int) Cache::get('seoLanding:version', 1);
    }
}
