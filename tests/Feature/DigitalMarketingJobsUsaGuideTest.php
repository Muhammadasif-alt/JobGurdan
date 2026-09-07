<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DigitalMarketingJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const DIGIMKT_SLUG = 'digital-marketing-jobs-in-usa';

const DIGIMKT_APPLY_URL = 'https://www.indeed.com/q-digital-marketing-jobs.html';

beforeEach(function () {
    $this->seed(DigitalMarketingJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', DIGIMKT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/digital-marketing-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', DIGIMKT_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('anchors pay on the BLS occupation and raises the ceiling the draft capped', function () {
    // The draft capped the specialist band near $95,000; the top tenth of the
    // occupation is above $155,480.
    $body = Blog::where('slug', DIGIMKT_SLUG)->value('content');

    expect($body)->toContain('$78,760')
        ->toContain('$43,390')
        ->toContain('$155,480');
});

it('backs the positive outlook with the projection instead of asserting it', function () {
    $response = get('/blog/'.DIGIMKT_SLUG)->assertOk();

    $response->assertSee('grow 7 per cent from 2025 to 2035', false)
        ->assertSee('82,000 openings a year', false);
});

it('corrects the cookie deprecation that courses still teach', function () {
    // Google abandoned third-party cookie deprecation on 22 April 2025.
    $body = Blog::where('slug', DIGIMKT_SLUG)->value('content');

    expect($body)->toContain('22 April 2025')
        ->toContain('would not deprecate third-party cookies in Chrome at all')
        ->toContain('Universal Analytics');
});

it('prices the certifications the draft listed as equivalent', function () {
    $body = Blog::where('slug', DIGIMKT_SLUG)->value('content');

    expect($body)->toContain('HubSpot Academy &mdash; free')
        ->toContain('$49 a month')
        ->toContain('$99 to $150 each');
});

it('carries the FTC disclosure duty attached to social media client work', function () {
    $body = Blog::where('slug', DIGIMKT_SLUG)->value('content');

    expect($body)->toContain('Endorsement Guides took effect on 26 July 2023')
        ->toContain('material connection')
        ->toContain('clearly and conspicuously')
        // Freelance income is self-employment, as across this series.
        ->toContain('15.3%');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', DIGIMKT_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('SEO jobs USA');

    get('/blog/'.DIGIMKT_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    // The draft's link carried a ?vjk= search-preview parameter; it is stripped.
    expect(app(StructuredDataService::class)->describesSingleVacancy(DIGIMKT_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.DIGIMKT_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(DigitalMarketingJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', DIGIMKT_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', DIGIMKT_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('median of $78,760');
});

it('links across the digital career cluster in both directions', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.DIGIMKT_SLUG)->assertOk()
        ->assertSee('/blog/web-developer-jobs-in-usa', false)
        ->assertSee('/blog/graphic-designer-jobs-in-usa', false)
        ->assertSee('/blog/digital-marketing-expert-seo-job-at-urban-solar-remote-pakistan', false);

    get('/blog/web-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.DIGIMKT_SLUG, false);
});
