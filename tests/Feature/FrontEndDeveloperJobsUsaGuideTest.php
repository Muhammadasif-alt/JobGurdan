<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder;
use Database\Seeders\FullStackDeveloperJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const FRONTEND_SLUG = 'front-end-developer-jobs-in-usa';

const FRONTEND_APPLY_URL = 'https://www.indeed.com/q-front-end-developer-jobs.html';

beforeEach(function () {
    $this->seed(FrontEndDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', FRONTEND_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/front-end-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', FRONTEND_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('sends readers to front end listings rather than the full stack search', function () {
    // The draft's apply button pointed at the full stack search with a ?vjk=
    // copied from the software developer draft.
    $blog = Blog::where('slug', FRONTEND_SLUG)->first();

    expect($blog->content)->toContain(FRONTEND_APPLY_URL)
        ->and($blog->content)->not->toContain('q-full-stack-developer-jobs.html')
        ->and($blog->content)->not->toContain('vjk=');
});

it('anchors pay on the BLS occupation front end actually belongs to', function () {
    $body = Blog::where('slug', FRONTEND_SLUG)->value('content');

    expect($body)->toContain('$92,650')
        ->toContain('$48,100')
        ->toContain('$162,290');
});

it('carries the accessibility rule and litigation that make it paid work', function () {
    // The differentiator for this guide, and absent from the draft entirely.
    $response = get('/blog/'.FRONTEND_SLUG)->assertOk();

    $response->assertSee('WCAG 2.1 Level AA', false)
        ->assertSee('26 April 2027', false)
        ->assertSee('26 April 2028', false)
        ->assertSee('3,117 federal website accessibility lawsuits', false)
        // The rule reaches agency and contractor work, not only in-house teams.
        ->assertSee('provided through its vendors and licensors', false);
});

it('gives the current Core Web Vitals metric and its threshold', function () {
    $body = Blog::where('slug', FRONTEND_SLUG)->value('content');

    expect($body)->toContain('INP replaced First Input Delay on 12 March 2024')
        ->toContain('200 milliseconds or less at the 75th percentile')
        ->toContain('CLS');
});

it('defers the working-from-abroad claim to the guide that covers it', function () {
    // The draft repeated "US-level rates" here; the full stack guide corrects
    // it at length, so this page states it briefly and links rather than
    // duplicating the argument across two pages.
    $body = Blog::where('slug', FRONTEND_SLUG)->value('content');

    expect($body)->toContain('be careful with the claim that it pays US rates')
        ->toContain('/blog/full-stack-developer-jobs-in-usa');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', FRONTEND_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('React developer jobs USA');

    get('/blog/'.FRONTEND_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(FRONTEND_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.FRONTEND_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(FrontEndDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', FRONTEND_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', FRONTEND_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('WCAG 2.1 Level AA');
});

it('links into the engineering cluster in both directions', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);
    $this->seed(FullStackDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.FRONTEND_SLUG)->assertOk()
        ->assertSee('/blog/web-developer-jobs-in-usa', false)
        ->assertSee('/blog/software-developer-jobs-in-usa', false)
        ->assertSee('/blog/graphic-designer-jobs-in-usa', false);

    get('/blog/web-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.FRONTEND_SLUG, false);
});
