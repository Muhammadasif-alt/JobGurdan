<?php

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Database\Seeders\WorkVisasPakistanis2026CountryGuideBlogSeeder;

/**
 * AdSense marked jobgader.com "Needs attention" on 8 November 2026 for
 * "Google-served ads on screens with replicated content" and low-value content.
 * The ad script loaded on every page, including the portal listings and other
 * pages already kept out of the index. Ads now load only on indexable pages,
 * and vacancies with very short descriptions are noindexed.
 */
const ADSENSE_SCRIPT = 'pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';

function adsTestJob(string $position, string $description, string $advertiserName = 'Real Employer Ltd'): Job
{
    $category = Category::firstOrCreate(['slug' => 'ads-test'], ['name' => 'Ads Test']);
    $location = Location::firstOrCreate(['name' => 'Adsville'], ['area' => 'Nationwide', 'country' => 'Testland']);
    $advertiser = Advertiser::firstOrCreate(['name' => $advertiserName]);

    return Job::create([
        'category_id' => $category->id,
        'advertiser_id' => $advertiser->id,
        'location_id' => $location->id,
        'position' => $position,
        'description' => $description,
        'job_type' => 'On-site',
        'application_url' => 'https://www.gov.uk/find-a-job',
    ]);
}

function adsTestJobUrl(Job $job): string
{
    return route('jobs.show', \Illuminate\Support\Str::slug($job->position.'-'.$job->location->name));
}

it('loads the ad script on an indexable guide', function () {
    $this->seed(WorkVisasPakistanis2026CountryGuideBlogSeeder::class);

    $this->get('/blog/'.WorkVisasPakistanis2026CountryGuideBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee(ADSENSE_SCRIPT, false)
        ->assertDontSee('noindex', false);
});

it('loads no ad script on a noindexed portal listing or the portals page', function () {
    $resource = adsTestJob('Portal Listing', '<p>'.str_repeat('Official portal guidance word. ', 60).'</p>', 'Gov Pages (Aggregated)');

    expect($resource->fresh()->isResource())->toBeTrue();

    $this->get(adsTestJobUrl($resource))
        ->assertOk()
        ->assertSee('noindex, follow', false)
        ->assertDontSee(ADSENSE_SCRIPT, false);

    $this->get(route('jobs.portals'))
        ->assertOk()
        ->assertDontSee(ADSENSE_SCRIPT, false);
});

it('noindexes a thin vacancy, drops its ads and keeps it out of the job sitemap', function () {
    $thin = adsTestJob('Thin Vacancy', '<p>Short description only.</p>');

    expect($thin->isThin())->toBeTrue();

    $this->get(adsTestJobUrl($thin))
        ->assertOk()
        ->assertSee('noindex, follow', false)
        ->assertDontSee(ADSENSE_SCRIPT, false);

    adsTestJob('Full Vacancy', '<p>'.str_repeat('Clear duties pay and hours described here. ', 30).'</p>');

    $this->get('/sitemap-jobs-1.xml')->assertOk()
        ->assertSee('full-vacancy', false)
        ->assertDontSee('thin-vacancy', false);
});

it('keeps a full vacancy indexable, with ads and in the job sitemap', function () {
    $full = adsTestJob('Full Vacancy', '<p>'.str_repeat('Clear duties pay and hours described here. ', 30).'</p>');

    expect($full->isThin())->toBeFalse();

    $this->get(adsTestJobUrl($full))
        ->assertOk()
        ->assertDontSee('noindex', false)
        ->assertSee(ADSENSE_SCRIPT, false);

    $this->get('/sitemap-jobs-1.xml')->assertOk()->assertSee('full-vacancy', false);
});
