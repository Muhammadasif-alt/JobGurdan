<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const SWDEV_SLUG = 'software-developer-jobs-in-usa';

const SWDEV_APPLY_URL = 'https://www.indeed.com/q-software-developer-jobs.html';

beforeEach(function () {
    $this->seed(SoftwareDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', SWDEV_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/software-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships the featured image it references', function () {
    // No dedicated image set has arrived for this post yet, so the featured
    // image is a placeholder and there are no inline figures.
    $blog = Blog::where('slug', SWDEV_SLUG)->first();

    expect(file_exists(storage_path('app/public/'.$blog->featured_image)))->toBeTrue();
});

it('raises every salary band the draft set too low', function () {
    // The draft put entry at $65,000-$90,000 when the occupation's bottom tenth
    // is $82,460, and capped senior below where the top tenth begins.
    $body = Blog::where('slug', SWDEV_SLUG)->value('content');

    expect($body)->toContain('$135,980')
        ->toContain('$82,460')
        ->toContain('$214,670')
        ->toContain('Both are low');
});

it('qualifies the talent-shortage framing the draft asserted', function () {
    $body = Blog::where('slug', SWDEV_SLUG)->value('content');

    expect($body)->toContain('grow 10 per cent from 2025 to 2035')
        ->toContain('106,100 openings a year')
        ->toContain('the junior end of this market is competitive');
});

it('explains the H-1B lottery and the cap-exempt route around it', function () {
    $response = get('/blog/'.SWDEV_SLUG)->assertOk();

    $response->assertSee('65,000 a year plus 20,000', false)
        ->assertSee('are exempt from the H-1B cap', false)
        ->assertSee('at any time of year, with no lottery', false);
});

it('gives the OPT and L-1 detail the draft listed without specifics', function () {
    $body = Blog::where('slug', SWDEV_SLUG)->value('content');

    expect($body)->toContain('Optional Practical Training gives up to 12 months')
        ->toContain('24 months &mdash; 36 in total')
        ->toContain('E-Verify')
        ->toContain('already worked for a related entity of the same employer abroad');
});

it('states the current status of the contested H-1B payment', function () {
    // Imposed 21 September 2025, guidance vacated 8 June 2026, stay denied
    // 24 July 2026. The page must not present it as currently enforced.
    $body = Blog::where('slug', SWDEV_SLUG)->value('content');

    expect($body)->toContain('8 June 2026')
        ->toContain('24 July 2026')
        ->toContain('not currently being enforced, and the litigation is live');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', SWDEV_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('OPT jobs for international students');

    get('/blog/'.SWDEV_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    // The draft's link carried a ?vjk= search-preview parameter; it is stripped.
    expect(app(StructuredDataService::class)->describesSingleVacancy(SWDEV_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.SWDEV_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(SoftwareDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', SWDEV_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', SWDEV_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('median of $135,980');
});

it('links to and from the web developer guide with one agreed median', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.SWDEV_SLUG)->assertOk()
        ->assertSee('/blog/web-developer-jobs-in-usa', false)
        ->assertSee('/blog/digital-marketing-jobs-in-usa', false);

    // Both guides must quote the same BLS figure for this occupation.
    expect(Blog::where('slug', 'web-developer-jobs-in-usa')->value('content'))
        ->toContain('$135,980')
        ->not->toContain('$132,684');

    get('/blog/web-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.SWDEV_SLUG, false);
});
