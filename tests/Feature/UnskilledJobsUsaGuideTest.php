<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HotelJobsBlogSeeder;
use Database\Seeders\UnskilledJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const UNSKILLED_SLUG = 'unskilled-jobs-in-usa-for-foreigners';

const UNSKILLED_APPLY_URL = 'https://www.indeed.com/q-unskilled-jobs.html';

beforeEach(function () {
    $this->seed(UnskilledJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', UNSKILLED_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/unskilled-jobs-in-usa-for-foreigners.jpg')
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', UNSKILLED_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('says who sets the wage on the sponsored routes', function () {
    // The draft presented the ranges as employer-set. H-2A pays the state
    // Adverse Effect Wage Rate and H-2B a DOL prevailing wage, both published.
    $body = Blog::where('slug', UNSKILLED_SLUG)->value('content');

    expect($body)->toContain('Adverse Effect Wage Rate')
        ->toContain('prevailing wage')
        ->toContain('an offer below the applicable rate is unlawful');
});

it('refuses the 24 to 36 month EB-3 timeline', function () {
    $body = Blog::where('slug', UNSKILLED_SLUG)->value('content');

    expect($body)->toContain('It does not take 24 to 36 months')
        ->toContain('retrogressed for every country of birth')
        ->not->toContain('processing typically takes');
});

it('states that no recruitment fee may be charged', function () {
    $response = get('/blog/'.UNSKILLED_SLUG)->assertOk();

    $response->assertSee('prohibit charging workers recruitment fees')
        ->assertSee('housing at no cost');
});

it('uses a clean apply link rather than the ad tracking URL', function () {
    $job = Job::where('application_url', UNSKILLED_APPLY_URL)->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->not->toContain('gclid')
        ->and($job->application_url)->not->toContain('utm_');

    expect(Blog::where('slug', UNSKILLED_SLUG)->value('content'))->not->toContain('gclid');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', UNSKILLED_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))->not->toContain('H-2A visa jobs');

    get('/blog/'.UNSKILLED_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(UNSKILLED_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.UNSKILLED_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one United States listing and links to the sector guides', function () {
    $this->seed(UnskilledJobsUsaBlogSeeder::class);
    $this->seed(HotelJobsBlogSeeder::class);

    $jobs = Job::where('application_url', UNSKILLED_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and($jobs->first()->location->country)->toBe('United States');

    get('/blog/'.UNSKILLED_SLUG)->assertOk()
        ->assertSee('/blog/hotel-jobs-in-usa-for-foreigners', false);
});
