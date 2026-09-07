<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AtsResumeWriterCanadaBlogSeeder;
use Database\Seeders\AtsResumeWriterUsaBlogSeeder;

use function Pest\Laravel\get;

const ATS_CA_SLUG = 'ats-resume-writer-jobs-in-canada';

const ATS_CA_APPLY_URL = 'https://ca.indeed.com/q-resume-writer-jobs.html';

beforeEach(function () {
    $this->seed(AtsResumeWriterCanadaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', ATS_CA_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/ats-resume-writer-jobs-in-canada.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', ATS_CA_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('corrects the marketplace hourly rate with the actual Canadian salary data', function () {
    // The draft quoted $35-$70+/hour as if it were earnings. That is the rate
    // writers list for billable time; the salary data is roughly half of it,
    // and a staff post in Ontario sits near the minimum wage.
    $body = Blog::where('slug', ATS_CA_SLUG)->value('content');

    expect($body)->toContain('$17 an hour')
        ->toContain('$40,225')
        ->toContain('$17.60 an hour, rising to $17.95 on 1 October 2026')
        ->toContain('listed rates for billable time');
});

it('gives the current Upwork fee rather than the retired flat rate', function () {
    // Upwork replaced the flat 10% with a per-contract variable fee on
    // 1 May 2025; contracts opened before that keep the 20/10/5 tiers.
    $body = Blog::where('slug', ATS_CA_SLUG)->value('content');

    expect($body)->toContain('has not been a flat 10% since 1 May 2025')
        ->toContain('0% to 15% set per contract');
});

it('treats the work as self-employment and covers CPP, GST/HST and T2125', function () {
    $response = get('/blog/'.ATS_CA_SLUG)->assertOk();

    $response->assertSee('11.9%')
        ->assertSee('T2125')
        ->assertSee('$30,000')
        // Zero-rated exports still count toward the small-supplier threshold,
        // which catches writers billing only American clients.
        ->assertSee('zero-rated supply is still a taxable supply');
});

it('names the Canadian credential and separates it from the American one', function () {
    $body = Blog::where('slug', ATS_CA_SLUG)->value('content');

    expect($body)->toContain('Career Professionals of Canada')
        ->toContain('80% or better')
        ->toContain('$295 for members or $470 for non-members')
        ->toContain('active membership is required to keep the designation');
});

it('carries the OHRC position on the Canadian experience barrier', function () {
    // The draft omitted this entirely, and it is the question every newcomer
    // client asks the writer they hire.
    $body = Blog::where('slug', ATS_CA_SLUG)->value('content');

    expect($body)->toContain('Ontario Human Rights Commission')
        ->toContain('prima facie discrimination')
        ->toContain('15 July 2013');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', ATS_CA_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Resume writer salary Toronto');

    get('/blog/'.ATS_CA_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(ATS_CA_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.ATS_CA_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one Canadian listing and does not duplicate it on a re-run', function () {
    $this->seed(AtsResumeWriterCanadaBlogSeeder::class);

    $jobs = Job::where('application_url', ATS_CA_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', ATS_CA_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Canada')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('Contract work is self-employment');
});

it('links to and from the USA guide so the pair reads as siblings', function () {
    $this->seed(AtsResumeWriterUsaBlogSeeder::class);

    get('/blog/'.ATS_CA_SLUG)->assertOk()
        ->assertSee('/blog/ats-resume-writer-jobs-in-usa', false)
        ->assertSee('/blog/ai-content-writer-jobs-in-usa', false)
        ->assertSee('/blog/remote-data-entry-jobs', false);

    get('/blog/ats-resume-writer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.ATS_CA_SLUG, false);
});
