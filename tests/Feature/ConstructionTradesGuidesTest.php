<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\ConstructionLabourerJobsDubaiSaudiBlogSeeder;
use Database\Seeders\SiteSupervisorForemanAbroadBlogSeeder;
use Database\Seeders\TilerPlastererMasonJobsOverseasBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

dataset('construction trades guides', [
    'tiler plasterer mason' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', 'blogs/tiler-plasterer-mason-jobs-overseas.jpg', ['tiler-plasterer-mason-jobs-overseas-trio.jpg', 'tiler-plasterer-mason-jobs-overseas-blockwork.jpg']],
    'labourer' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'construction-labourer-jobs-in-dubai-and-saudi-arabia', 'blogs/construction-labourer-jobs-dubai-saudi.jpg', ['construction-labourer-jobs-dubai-saudi-concrete.jpg', 'construction-labourer-jobs-dubai-saudi-tile-cutting.jpg']],
    'supervisor' => [SiteSupervisorForemanAbroadBlogSeeder::class, 'how-to-become-a-site-supervisor-or-foreman-abroad', 'blogs/site-supervisor-foreman-abroad.jpg', ['site-supervisor-foreman-abroad-blockwork.jpg', 'site-supervisor-foreman-abroad-drawings.jpg']],
]);

it('publishes each guide with its own images and SEO fields', function (string $seeder, string $slug, string $featured, array $inline) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($featured)
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);

    foreach ($inline as $image) {
        expect($blog->content)->toContain($image);
    }

    expect($blog->content)->not->toContain(basename($featured));

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
})->with('construction trades guides');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('construction trades guides');

it('links to no aggregator, recruiter or job ID', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = (string) Blog::where('slug', $slug)->value('content');

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    foreach (['indeed', 'glassdoor', 'gulftalent', 'naukrigulf', 'bayt', 'wazaif', 'newjobs', 'argc', 'gulfwalkin', 'taha airwaves', 'tarve', 'keller'] as $banned) {
        expect(strtolower($content))->not->toContain($banned);
    }
})->with('construction trades guides');

it('renders each guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('construction trades guides');

it('states the Gulf rules in every guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect($content)->toContain('Article 6 of Federal Decree-Law No. 33 of 2021')
        ->and($content)->toContain('12:30pm to 3pm')
        ->and($content)->toContain('Qiwa');
})->with('construction trades guides');

it('gives the current UK sponsorship position by occupation code', function (string $claim) {
    $this->seed(TilerPlastererMasonJobsOverseasBlogSeeder::class);

    expect(Blog::where('slug', 'tiler-plasterer-and-mason-jobs-overseas')->value('content'))->toContain($claim);
})->with([
    'tsl cut-off' => 'Since 22 July 2025',
    'tilers listed' => '<strong>5322 Floorers and wall tilers</strong>: on the Temporary Shortage List',
    'going rate' => '&pound;33,400 a year (&pound;17.13 an hour)',
    'plasterers not listed' => '<strong>5321 Plasterers</strong>: not on the list',
    'bricklayers not listed' => '<strong>5312 Bricklayers and masons</strong>: not on the list',
    'professional verification' => 'Professional Verification',
    'saudi article 40' => 'Article 40 of the Saudi Labour Law',
]);

it('drops the recruiter and job-board figures', function (string $seeder, string $slug, string $figure) {
    $this->seed($seeder);

    expect(Blog::where('slug', $slug)->value('content'))->not->toContain($figure);
})->with([
    'recruiter openings' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', '180 openings'],
    'recruiter aed pay' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', '1,600'],
    'recruiter sar pay' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', '1,100'],
    'sponsored job pay' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', '37,002'],
    'stale eligibility' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', 'eligible Medium Skilled'],
    'job board aed range' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'construction-labourer-jobs-in-dubai-and-saudi-arabia', '2,500'],
    'job board sar range' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'construction-labourer-jobs-in-dubai-and-saudi-arabia', '2,200'],
    'vacancy count' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'construction-labourer-jobs-in-dubai-and-saudi-arabia', '1,877'],
    'job board count' => [SiteSupervisorForemanAbroadBlogSeeder::class, 'how-to-become-a-site-supervisor-or-foreman-abroad', '600+'],
    'vacancy deadline' => [SiteSupervisorForemanAbroadBlogSeeder::class, 'how-to-become-a-site-supervisor-or-foreman-abroad', '30 October 2026'],
]);

it('creates listings with official apply URLs, no salary and no markup', function (string $seeder, string $position, string $applyUrl, string $country) {
    $this->seed($seeder);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull("no listing created for {$position}")
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('construction-trades')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uae mason tiler' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'Mason and Tiler — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'uk tiler' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'Floorer and Wall Tiler — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'uae labourer' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'Construction Labourer and Helper — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'saudi labourer' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'Construction Labourer and Helper — Saudi', 'https://www.hrsd.gov.sa/en', 'Saudi Arabia'],
    'uae supervisor' => [SiteSupervisorForemanAbroadBlogSeeder::class, 'Site Supervisor and Foreman — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
]);

it('cross-links the three guides in both directions', function (string $fromSeeder, string $fromSlug, string $toSeeder, string $toSlug) {
    $this->seed($fromSeeder);
    $this->seed($toSeeder);

    expect(Blog::where('slug', $fromSlug)->value('content'))->toContain('/blog/'.$toSlug)
        ->and(Blog::where('slug', $toSlug)->value('content'))->toContain('/blog/'.$fromSlug);
})->with([
    'tiler and labourer' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'construction-labourer-jobs-in-dubai-and-saudi-arabia'],
    'tiler and supervisor' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas', SiteSupervisorForemanAbroadBlogSeeder::class, 'how-to-become-a-site-supervisor-or-foreman-abroad'],
    'labourer and supervisor' => [ConstructionLabourerJobsDubaiSaudiBlogSeeder::class, 'construction-labourer-jobs-in-dubai-and-saudi-arabia', SiteSupervisorForemanAbroadBlogSeeder::class, 'how-to-become-a-site-supervisor-or-foreman-abroad'],
]);

it('links out to the existing Gulf construction and trades guides', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    foreach (['construction-jobs-in-saudi-arabia-with-visa-sponsorship', 'plumber-jobs-in-uae-and-saudi-arabia', 'ac-technician-jobs-in-dubai-and-saudi-arabia'] as $existing) {
        expect($content)->toContain('/blog/'.$existing);
    }
})->with('construction trades guides');
