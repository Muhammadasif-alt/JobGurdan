<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\JavaDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const JAVA_SLUG = 'java-developer-jobs-in-usa';

const JAVA_APPLY_URL = 'https://www.indeed.com/q-java-developer-jobs.html';

beforeEach(function () {
    $this->seed(JavaDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', JAVA_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/java-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', JAVA_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('repairs the draft apply link, which was not a live search path', function () {
    // The draft pointed at https://indeed.com/q-java-developer-usa-jobs.html.
    $blog = Blog::where('slug', JAVA_SLUG)->first();

    expect($blog->content)->toContain(JAVA_APPLY_URL)
        ->and($blog->content)->not->toContain('q-java-developer-usa-jobs')
        ->and($blog->content)->not->toContain('https://indeed.com/');
});

it('corrects a senior band that started at the occupation median', function () {
    // The draft put senior Java at "$135,000-$180,000+". $135,980 is the median
    // for the whole occupation as of May 2025.
    $response = get('/blog/'.JAVA_SLUG)->assertOk();

    $response->assertSee('$135,980', false)
        ->assertSee('$214,670', false)
        ->assertSee('the median for the whole occupation', false)
        ->assertSee('that is your floor to negotiate up from, not your target', false);
});

it('explains what corp-to-corp actually requires from the contractor', function () {
    // This is the page's differentiator: the contract structures behind the
    // Java staffing market, which no other guide in the cluster covers.
    $body = Blog::where('slug', JAVA_SLUG)->value('content');

    expect($body)->toContain('A registered US business entity')
        ->toContain('An EIN')
        ->toContain('certificate of insurance')
        ->toContain('It is not a work authorisation');
});

it('states plainly that solo C2C is closed to H-1B holders', function () {
    $response = get('/blog/'.JAVA_SLUG)->assertOk();

    $response->assertSee('cannot work solo C2C through their own company', false)
        ->assertSee('W-2 employee of a consulting or staffing firm', false)
        ->assertSee('third-party worksite rules', false);
});

it('gives the live regulatory position on worker classification', function () {
    // Unsettled at the time of writing: 2024 rule unenforced since May 2025,
    // 26 February 2026 proposal to restore the 2021 test, comments closed
    // 28 April 2026, not finalised. The page must not present it as settled.
    $body = Blog::where('slug', JAVA_SLUG)->value('content');

    expect($body)->toContain('stopped being enforced in May 2025')
        ->toContain('26 February 2026')
        ->toContain('28 April 2026')
        ->toContain('had not been finalised at the time of writing')
        ->toContain('Check the current position with the DOL');
});

it('warns that vendor layers sit between the rate and the client budget', function () {
    $body = Blog::where('slug', JAVA_SLUG)->value('content');

    expect($body)->toContain('Are you the prime vendor on this requirement')
        ->toContain('each layer takes a margin');
});

it('defers the freelance tax mechanics to the guide that covers them', function () {
    // Stated once in the web developer guide and linked, not re-argued.
    $body = Blog::where('slug', JAVA_SLUG)->value('content');

    expect($body)->toContain('/blog/web-developer-jobs-in-usa')
        ->toContain('15.3 per cent self-employment tax');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', JAVA_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Java C2C jobs');

    get('/blog/'.JAVA_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(JAVA_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.JAVA_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(JavaDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', JAVA_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', JAVA_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('W-2, 1099 and corp-to-corp are three different legal relationships');
});

it('links into the engineering cluster in both directions', function () {
    $this->seed(SoftwareDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.JAVA_SLUG)->assertOk()
        ->assertSee('/blog/software-developer-jobs-in-usa', false)
        ->assertSee('/blog/python-developer-jobs-in-usa', false)
        ->assertSee('/blog/react-developer-jobs-in-usa', false)
        ->assertSee('/blog/full-stack-developer-jobs-in-usa', false);

    get('/blog/software-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.JAVA_SLUG, false);
});
