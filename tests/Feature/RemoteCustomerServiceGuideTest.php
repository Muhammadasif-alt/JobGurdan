<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\RemoteCustomerServiceJobsBlogSeeder;
use Database\Seeders\RemoteDataEntryJobsBlogSeeder;
use Database\Seeders\RemoteJobsNoExperienceBlogSeeder;

use function Pest\Laravel\get;

const SUPPORT_SLUG = 'remote-customer-service-jobs';

const SUPPORT_APPLY_URL = 'https://pk.indeed.com/q-customer-service-remote-jobs.html';

beforeEach(function () {
    $this->seed(RemoteCustomerServiceJobsBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', SUPPORT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/remote-customer-service-jobs.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', SUPPORT_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('names the minimum wage floor beside the advertised band', function () {
    $body = Blog::where('slug', SUPPORT_SLUG)->value('content');

    expect($body)->toContain('40,000')
        ->toContain('40,700')
        ->toContain('below the legal floor');
});

it('warns that many postings tagged remote are really hybrid', function () {
    // The draft assumed every listing is genuinely remote. In Pakistan a large
    // share are hybrid or remote-during-training only, and the wording is the
    // same either way.
    $response = get('/blog/'.SUPPORT_SLUG)->assertOk();

    $response->assertSee('remote during training only')
        ->assertSee('hybrid');
});

it('sets out what the employer monitors', function () {
    $body = Blog::where('slug', SUPPORT_SLUG)->value('content');

    expect($body)->toContain('Call recording is standard')
        ->toContain('screen recording');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', SUPPORT_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Customer service jobs for students');

    get('/blog/'.SUPPORT_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(SUPPORT_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.SUPPORT_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one remote listing and does not duplicate it on a re-run', function () {
    $this->seed(RemoteCustomerServiceJobsBlogSeeder::class);

    $jobs = Job::where('application_url', SUPPORT_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', SUPPORT_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('genuinely remote');
});

it('links to the other remote guides', function () {
    $this->seed(RemoteJobsNoExperienceBlogSeeder::class);
    $this->seed(RemoteDataEntryJobsBlogSeeder::class);

    get('/blog/'.SUPPORT_SLUG)->assertOk()
        ->assertSee('/blog/remote-jobs-in-pakistan-with-no-experience', false)
        ->assertSee('/blog/remote-data-entry-jobs', false);
});
