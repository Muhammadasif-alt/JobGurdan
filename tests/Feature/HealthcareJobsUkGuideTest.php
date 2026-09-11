<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CaregiverUkBlogSeeder;
use Database\Seeders\HealthcareJobsUkBlogSeeder;

use function Pest\Laravel\get;

const HEALTH_UK_SLUG = 'healthcare-jobs-in-the-uk';

const HEALTH_UK_APPLY_URL = 'https://uk.indeed.com/q-healthcare-jobs.html?vjk=4ff6f1084283ad4e';

beforeEach(function () {
    $this->seed(HealthcareJobsUkBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', HEALTH_UK_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/healthcare-jobs-in-uk.jpg')
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', HEALTH_UK_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('does not repeat the claim that care roles are sponsorable', function () {
    // The draft said the visa is available "increasingly for care workers and
    // healthcare assistants". Care workers closed on 22 July 2025, NHS employer
    // or not; hospital healthcare assistants (6131) remain sponsorable only at
    // Band 3 or higher.
    $response = get('/blog/'.HEALTH_UK_SLUG)->assertOk();

    $response->assertSee('closed to new overseas applicants')
        ->assertSee('22 July 2025')
        ->assertSee('Working for the NHS does not reopen the route')
        ->assertSee('Band 3 or higher')
        ->assertSee('Band 1 and Band 2 posts cannot');

    expect($response->getContent())->not->toContain('increasingly for care workers');
});

it('puts registration ahead of the visa as the limiting step', function () {
    $body = Blog::where('slug', HEALTH_UK_SLUG)->value('content');

    expect($body)->toContain('Registration Is the Real Gate')
        ->toContain('NMC')
        ->toContain('OSCE')
        ->toContain('Plan on the registration timeline rather than the visa timeline');
});

it('explains how TRAC shortlisting is actually scored', function () {
    $body = Blog::where('slug', HEALTH_UK_SLUG)->value('content');

    expect($body)->toContain('person specification')
        ->toContain('essential criterion explicitly');
});

it('defers the care worker question to the existing care page', function () {
    // Keeps the two UK pages off the same query rather than restating it here.
    get('/blog/'.HEALTH_UK_SLUG)->assertOk()
        ->assertSee('/blog/caregiver-jobs-in-uk-with-visa-sponsorship', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', HEALTH_UK_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))->not->toContain('TRAC jobs NHS');

    get('/blog/'.HEALTH_UK_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(HEALTH_UK_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.HEALTH_UK_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one United Kingdom listing and does not duplicate it on a re-run', function () {
    $this->seed(HealthcareJobsUkBlogSeeder::class);
    $this->seed(CaregiverUkBlogSeeder::class);

    $jobs = Job::where('application_url', HEALTH_UK_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', HEALTH_UK_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United Kingdom')
        ->and($jobs->first()->description)->toContain('22 July 2025');
});
