<?php

use App\Models\Advertiser;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Models\Scholarship;
use Database\Seeders\WorkVisasPakistanis2026CountryGuideBlogSeeder;

/**
 * Fixes from the AdSense readiness audit of 26 October 2026.
 *
 * 1. Laravel 12.39 added a @context Blade directive, so every JSON-LD block
 *    written as '@context' inside {!! json_encode(...) !!} was compiled into
 *    PHP and the page printed "<?php $__contextArgs" as the key instead.
 * 2. Eighty-nine guides link their call-to-action button to
 *    /categories/career-advice or /categories/visa-sponsorship, which are blog
 *    categories, so the job category route sent readers to a 404.
 * 3. Category and location pages with no open vacancy, and the "(Aggregated)"
 *    placeholder advertisers, were indexable and listed in the sitemaps.
 */

/**
 * @return list<array<string, mixed>>
 */
function adsenseAuditJsonLd(string $html): array
{
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);

    return array_values(array_filter(
        array_map(fn (string $block) => json_decode(trim($block), true), $matches[1]),
        'is_array'
    ));
}

function adsenseAuditJob(Category $category, Location $location, Advertiser $advertiser, string $position, string $listingType = Job::LISTING_VACANCY): Job
{
    return Job::create([
        'category_id' => $category->id,
        'advertiser_id' => $advertiser->id,
        'location_id' => $location->id,
        'position' => $position,
        'description' => '<p>'.$position.' at '.$advertiser->name.'.</p>',
        'job_type' => 'On-site',
        'application_url' => 'https://www.gov.uk/find-a-job',
        'listing_type' => $listingType,
    ]);
}

it('keeps the schema.org context on public pages instead of compiling it as a Blade directive', function () {
    $this->seed(WorkVisasPakistanis2026CountryGuideBlogSeeder::class);
    Scholarship::factory()->create();
    $category = Category::create(['name' => 'Test Trades', 'slug' => 'test-trades']);
    $location = Location::create(['name' => 'Testville', 'area' => 'Nationwide', 'country' => 'Testland']);
    $advertiser = Advertiser::create(['name' => 'Test Employer Ltd']);
    adsenseAuditJob($category, $location, $advertiser, 'Test Fitter');

    $paths = [
        '/', '/jobs', '/categories', '/locations', '/companies', '/blog', '/scholarships',
        '/categories/test-trades', '/location/'.$location->id, '/companies/'.$advertiser->id,
        '/blog/'.WorkVisasPakistanis2026CountryGuideBlogSeeder::SLUG,
    ];

    foreach ($paths as $path) {
        $html = $this->get($path)->assertOk()->getContent();

        expect(str_contains($html, '__contextArgs'))->toBeFalse('Compiled PHP leaked into '.$path);

        $blocks = adsenseAuditJsonLd($html);
        expect($blocks)->not->toBeEmpty();

        foreach ($blocks as $block) {
            foreach (array_keys($block) as $key) {
                expect(str_starts_with((string) $key, '<?php'))->toBeFalse('PHP key in JSON-LD on '.$path);
            }
        }

        $withContext = collect($blocks)->filter(fn (array $block) => ($block['@context'] ?? null) === 'https://schema.org');
        expect($withContext->isNotEmpty())->toBeTrue('No block on '.$path.' carries @context');
    }
});

it('redirects the guides\' blog category buttons that point under /categories', function () {
    BlogCatgories::create(['name' => 'Career Advice', 'slug' => 'career-advice', 'description' => 'Guides']);

    $this->get('/categories/career-advice')
        ->assertStatus(301)
        ->assertRedirect(route('blog.index', ['category' => 'career-advice']));

    $this->get('/categories/no-such-category')->assertNotFound();
});

it('keeps empty category, location and aggregated company pages out of the index and the sitemaps', function () {
    $emptyCategory = Category::create(['name' => 'Empty Trade', 'slug' => 'empty-trade']);
    $filledCategory = Category::create(['name' => 'Filled Trade', 'slug' => 'filled-trade']);
    $emptyLocation = Location::create(['name' => 'Nowhere', 'area' => 'Nationwide', 'country' => 'Nowhere']);
    $filledLocation = Location::create(['name' => 'Somewhere', 'area' => 'Nationwide', 'country' => 'Somewhere']);
    $aggregated = Advertiser::create(['name' => 'Nowhere Employers (Aggregated)']);
    $employer = Advertiser::create(['name' => 'Somewhere Builders Ltd']);

    adsenseAuditJob($filledCategory, $filledLocation, $employer, 'Site Carpenter');
    adsenseAuditJob($emptyCategory, $emptyLocation, $aggregated, 'Trade Worker - Portal Search', Job::LISTING_RESOURCE);

    expect($aggregated->isAggregated())->toBeTrue()
        ->and($employer->isAggregated())->toBeFalse();

    foreach (['/categories/empty-trade', '/location/'.$emptyLocation->id, '/companies/'.$aggregated->id] as $path) {
        $this->get($path)->assertOk()->assertSee('content="noindex, follow"', false);
    }

    foreach (['/categories/filled-trade', '/location/'.$filledLocation->id, '/companies/'.$employer->id] as $path) {
        $this->get($path)->assertOk()->assertDontSee('noindex');
    }

    $this->get('/sitemap-categories.xml')->assertOk()
        ->assertSee('<loc>'.url('/categories/filled-trade').'</loc>', false)
        ->assertDontSee('/categories/empty-trade');

    $this->get('/sitemap-locations.xml')->assertOk()
        ->assertSee('<loc>'.url('/location/'.$filledLocation->id).'</loc>', false)
        ->assertDontSee('<loc>'.url('/location/'.$emptyLocation->id).'</loc>', false);

    $this->get('/sitemap-companies.xml')->assertOk()
        ->assertSee('<loc>'.url('/companies/'.$employer->id).'</loc>', false)
        ->assertDontSee('<loc>'.url('/companies/'.$aggregated->id).'</loc>', false);
});
