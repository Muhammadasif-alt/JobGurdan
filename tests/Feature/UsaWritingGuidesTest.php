<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AiContentWriterUsaBlogSeeder;
use Database\Seeders\AtsResumeWriterUsaBlogSeeder;

use function Pest\Laravel\get;

const ATS_SLUG = 'ats-resume-writer-jobs-in-usa';

const AI_SLUG = 'ai-content-writer-jobs-in-usa';

beforeEach(function () {
    $this->seed(AtsResumeWriterUsaBlogSeeder::class);
    $this->seed(AiContentWriterUsaBlogSeeder::class);
});

it('publishes each guide with its SEO fields inside the snippet limits', function (string $slug, string $image) {
    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($image)
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
})->with([
    'ats' => [ATS_SLUG, 'blogs/ats-resume-writer-jobs-in-usa.jpg'],
    'ai' => [AI_SLUG, 'blogs/ai-content-writer-jobs-in-usa.jpg'],
]);

it('ships every image each guide references', function (string $slug) {
    $blog = Blog::where('slug', $slug)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
})->with([ATS_SLUG, AI_SLUG]);

it('separates the resume credentials from the LinkedIn one', function () {
    // The draft listed NCOPE beside CPRW as a resume-writing certification.
    // NCOPE is the NRWA's online profile credential and covers LinkedIn.
    $response = get('/blog/'.ATS_SLUG)->assertOk();

    $response->assertSee('not a resume certification')
        ->assertSee('Nationally Certified Online Profile Expert')
        ->assertSee('NCRW');
});

it('refuses the 75 per cent ATS rejection claim', function () {
    $body = Blog::where('slug', ATS_SLUG)->value('content');

    // The figure traces to a 2012 sales pitch by a company that closed in 2013;
    // recruiters say their systems do not auto-reject.
    expect($body)->toContain('no published research supports it')
        ->toContain('2012 sales pitch')
        ->toContain('do not auto-reject');
});

it('states what Google actually targets rather than penalising AI', function () {
    $body = Blog::where('slug', AI_SLUG)->value('content');

    expect($body)->toContain('scaled content abuse')
        ->toContain('regardless of how it was produced')
        ->not->toContain('Google penalises AI-written content is');
});

it('argues against per-word pay for editing work', function () {
    $response = get('/blog/'.AI_SLUG)->assertOk();

    $response->assertSee('per-word pay pays you less the better you do the job')
        ->assertSee('per-piece or hourly');
});

it('carries FAQ markup built from each post body', function (string $slug, string $notAQuestion) {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', $slug)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))->not->toContain($notAQuestion);

    get('/blog/'.$slug)->assertSee('"FAQPage"', false);
})->with([
    'ats' => [ATS_SLUG, 'CPRW certification'],
    'ai' => [AI_SLUG, 'Remote AI writer jobs'],
]);

it('drops JobPosting markup because both apply links are search pages', function (string $slug, string $applyUrl) {
    expect(app(StructuredDataService::class)->describesSingleVacancy($applyUrl))->toBeFalse();

    expect(get('/blog/'.$slug)->getContent())->not->toContain('"JobPosting"');
})->with([
    'ats' => [ATS_SLUG, 'https://www.indeed.com/q-resume-writer-jobs.html'],
    'ai' => [AI_SLUG, 'https://www.indeed.com/q-ai-writer-jobs.html'],
]);

it('creates one United States listing per guide and does not duplicate on a re-run', function (string $applyUrl, string $position) {
    $this->seed(AtsResumeWriterUsaBlogSeeder::class);
    $this->seed(AiContentWriterUsaBlogSeeder::class);

    $jobs = Job::where('application_url', $applyUrl)->get();

    expect($jobs)->toHaveCount(1)
        ->and($jobs->first()->position)->toBe($position)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull();
})->with([
    'ats' => ['https://www.indeed.com/q-resume-writer-jobs.html', 'ATS Resume Writer — Career Services and Staffing Firms'],
    'ai' => ['https://www.indeed.com/q-ai-writer-jobs.html', 'AI Content Writer — Agencies and SaaS Employers'],
]);

it('links the two writing guides to each other', function () {
    get('/blog/'.ATS_SLUG)->assertOk()->assertSee('/blog/'.AI_SLUG, false);

    get('/blog/'.AI_SLUG)->assertOk()->assertSee('/blog/'.ATS_SLUG, false);
});
