<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\GraphicDesignerJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const WEBDEV_SLUG = 'web-developer-jobs-in-usa';

const WEBDEV_APPLY_URL = 'https://www.indeed.com/q-web-developer-jobs.html';

beforeEach(function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', WEBDEV_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/web-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', WEBDEV_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('anchors pay on the BLS median and full distribution', function () {
    $body = Blog::where('slug', WEBDEV_SLUG)->value('content');

    expect($body)->toContain('$92,650')
        ->toContain('$48,100')
        ->toContain('$162,290');
});

it('corrects the inflated entry-level floor the draft carried twice', function () {
    // The draft said $60,000-$95,000 in one section and $55,000-$80,000 in
    // another. A tenth of the whole occupation earns under $48,100.
    $body = Blog::where('slug', WEBDEV_SLUG)->value('content');

    expect($body)->toContain('first roles below $60,000 are not unusual, they are normal')
        ->and($body)->not->toContain('$70,000&ndash;$85,000');
});

it('names the two better-paid occupations reachable from the role', function () {
    $response = get('/blog/'.WEBDEV_SLUG)->assertOk();

    $response->assertSee('$104,000')
        ->assertSee('$201,550')
        // The BLS Occupational Outlook Handbook figure, matching the software
        // developer guide; an earlier draft quoted a third-party OEWS number.
        ->assertSee('$135,980');
});

it('corrects the claim that Toptal is a bidding marketplace', function () {
    $body = Blog::where('slug', WEBDEV_SLUG)->value('content');

    expect($body)->toContain('accepts fewer than 3 per cent')
        ->toContain('five-stage screen')
        ->toContain('no bidding');
});

it('prices a freelance rate against tax and platform fees', function () {
    $body = Blog::where('slug', WEBDEV_SLUG)->value('content');

    expect($body)->toContain('15.3%')
        ->toContain('Schedule C')
        ->toContain('1099-NEC')
        ->toContain('has not been a flat 10% since 1 May 2025');
});

it('states the H-1B cap and the current status of the contested payment', function () {
    // Live litigation: imposed 21 September 2025, implementing guidance vacated
    // 8 June 2026, stay denied 24 July 2026. The page must not present the fee
    // as currently enforced.
    $body = Blog::where('slug', WEBDEV_SLUG)->value('content');

    expect($body)->toContain('65,000 visas a year plus 20,000')
        ->toContain('8 June 2026')
        ->toContain('24 July 2026')
        ->toContain('not currently being enforced, and it is live litigation')
        ->toContain('check the current USCIS guidance');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', WEBDEV_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('React developer jobs USA');

    get('/blog/'.WEBDEV_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    // The draft's link carried a ?vjk= search-preview parameter; it is stripped.
    expect(app(StructuredDataService::class)->describesSingleVacancy(WEBDEV_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.WEBDEV_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', WEBDEV_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', WEBDEV_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('median of $92,650');
});

it('links to and from the graphic designer guide it is the counterpart to', function () {
    $this->seed(GraphicDesignerJobsUsaBlogSeeder::class);

    get('/blog/'.WEBDEV_SLUG)->assertOk()
        ->assertSee('/blog/graphic-designer-jobs-in-usa', false)
        // The two weaker cross-discipline links were swapped for the engineering
        // siblings once those guides existed.
        ->assertSee('/blog/front-end-developer-jobs-in-usa', false)
        ->assertSee('/blog/full-stack-developer-jobs-in-usa', false)
        ->assertSee('/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern', false);

    get('/blog/graphic-designer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.WEBDEV_SLUG, false);
});
