<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AiContentWriterUsaBlogSeeder;
use Database\Seeders\CopywriterJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const COPY_SLUG = 'copywriter-jobs-in-usa';

const COPY_APPLY_URL = 'https://www.indeed.com/q-copywriter-jobs.html';

beforeEach(function () {
    $this->seed(CopywriterJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', COPY_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/copywriter-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', COPY_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('gives the pay figures the draft omitted entirely', function () {
    $body = Blog::where('slug', COPY_SLUG)->value('content');

    expect($body)->toContain('$76,910')
        ->toContain('$44,310')
        ->toContain('$139,870');
});

it('replaces the never-higher-demand claim with the projection and its exception', function () {
    // BLS projects 0% and names AI, but says advertising work continues, which
    // is this niche. Both halves have to be present or the page misleads.
    $body = Blog::where('slug', COPY_SLUG)->value('content');

    expect($body)->toContain('0 per cent &mdash; little or no change &mdash; from 2025 to 2035')
        ->toContain('dampen demand for these workers')
        ->toContain('continue to be needed for online media and advertising');
});

it('treats self-employment as the default state of the occupation', function () {
    $response = get('/blog/'.COPY_SLUG)->assertOk();

    $response->assertSee('65 per cent of writers and authors are self-employed', false)
        ->assertSee('15.3%')
        ->assertSee('Schedule C');
});

it('separates copywriting from content writing so the two guides do not compete', function () {
    $body = Blog::where('slug', COPY_SLUG)->value('content');

    expect($body)->toContain('Copywriting Is Not Content Writing')
        ->toContain('writing to cause an action')
        // The AI-content-and-Google ground belongs to the sibling guide.
        ->toContain('/blog/ai-content-writer-jobs-in-usa');
});

it('carries the FTC substantiation rule and the work-for-hire trap', function () {
    $body = Blog::where('slug', COPY_SLUG)->value('content');

    expect($body)->toContain('reasonable basis for an objective claim before the advertisement runs')
        ->toContain('Section 5 of the FTC Act')
        ->toContain('nine statutory categories')
        ->toContain('the copyright stays with the writer');
});

it('keeps the body on the USA and serves the off-topic queries from the tail', function () {
    // The draft gave a body section to "remote copywriter jobs europe" inside a
    // page about the USA, and repeated bolded keyword variants.
    $body = Blog::where('slug', COPY_SLUG)->value('content');

    $beforeTail = substr($body, 0, strpos($body, '<h2>People Also Search For</h2>'));

    expect($beforeTail)->not->toContain('europe')
        ->and($beforeTail)->not->toContain('jobs remote copywriter');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', COPY_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Email copywriter jobs');

    get('/blog/'.COPY_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    // The draft's link carried a ?vjk= search-preview parameter; it is stripped.
    expect(app(StructuredDataService::class)->describesSingleVacancy(COPY_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.COPY_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(CopywriterJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', COPY_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', COPY_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('median of $76,910');
});

it('links across the writing cluster in both directions', function () {
    $this->seed(AiContentWriterUsaBlogSeeder::class);

    get('/blog/'.COPY_SLUG)->assertOk()
        ->assertSee('/blog/digital-marketing-jobs-in-usa', false)
        ->assertSee('/blog/ats-resume-writer-jobs-in-usa', false)
        ->assertSee('/blog/graphic-designer-jobs-in-usa', false);

    get('/blog/ai-content-writer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.COPY_SLUG, false);
});
