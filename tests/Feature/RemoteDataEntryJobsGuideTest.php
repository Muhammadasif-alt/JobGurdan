<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\RemoteDataEntryJobsBlogSeeder;
use Database\Seeders\RemoteJobsNoExperienceBlogSeeder;

use function Pest\Laravel\get;

const DATA_ENTRY_SLUG = 'remote-data-entry-jobs';

const DATA_ENTRY_APPLY_URL = 'https://pk.indeed.com/q-remote-data-entry-jobs.html?vjk=1a56e64e4eb83374';

beforeEach(function () {
    $this->seed(RemoteDataEntryJobsBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', DATA_ENTRY_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/remote-data-entry-jobs.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', DATA_ENTRY_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('corrects the claim that Amazon posts work-from-home data entry', function () {
    // The draft said these roles come "directly through Amazon's own remote job
    // postings". The BBB has documented that phrase as an impersonation scam
    // ending in a paid enrolment kit.
    $response = get('/blog/'.DATA_ENTRY_SLUG)->assertOk();

    $response->assertSee('Amazon does not advertise work-from-home data entry')
        ->assertSee('third-party sellers')
        ->assertSee('enrolment');

    expect($response->getContent())
        ->not->toContain('directly through Amazon');
});

it('names the minimum wage floor beside the Pakistan band', function () {
    $body = Blog::where('slug', DATA_ENTRY_SLUG)->value('content');

    // The draft quoted PKR 25,000 as an ordinary floor; that is below the
    // notified minimum for a full-time role in Punjab, Sindh and KP.
    expect($body)->toContain('40,000')
        ->toContain('40,700')
        ->toContain('below the legal floor');
});

it('puts the scam patterns before the pay', function () {
    $body = Blog::where('slug', DATA_ENTRY_SLUG)->value('content');

    $scamPosition = strpos($body, 'How the Fake Version Works');
    $payPosition = strpos($body, 'Remote Data Entry Jobs in Pakistan');

    expect($scamPosition)->not->toBeFalse()
        ->and($scamPosition)->toBeLessThan($payPosition);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', DATA_ENTRY_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Amazon data entry jobs');

    get('/blog/'.DATA_ENTRY_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(DATA_ENTRY_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.DATA_ENTRY_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one remote listing and does not duplicate it on a re-run', function () {
    $this->seed(RemoteDataEntryJobsBlogSeeder::class);

    $jobs = Job::where('application_url', DATA_ENTRY_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', DATA_ENTRY_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('Amazon does not advertise');
});

it('links to and from the wider remote guide', function () {
    $this->seed(RemoteJobsNoExperienceBlogSeeder::class);

    get('/blog/'.DATA_ENTRY_SLUG)->assertOk()
        ->assertSee('/blog/remote-jobs-in-pakistan-with-no-experience', false);

    get('/blog/remote-jobs-in-pakistan-with-no-experience')->assertOk()
        ->assertSee('/blog/'.DATA_ENTRY_SLUG, false);
});
