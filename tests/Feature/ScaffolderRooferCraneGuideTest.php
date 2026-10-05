<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CarpenterJobsUkCanadaAustraliaBlogSeeder;
use Database\Seeders\ScaffolderRooferCraneOperatorJobsBlogSeeder;
use Database\Seeders\SiteSupervisorForemanAbroadBlogSeeder;
use Database\Seeders\TilerPlastererMasonJobsOverseasBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const SCAFFOLDER_SLUG = 'scaffolder-roofer-and-crane-operator-jobs-abroad';

beforeEach(function () {
    $this->seed(ScaffolderRooferCraneOperatorJobsBlogSeeder::class);
    $this->blog = Blog::where('slug', SCAFFOLDER_SLUG)->first();
});

it('publishes with its images and SEO fields', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/scaffolder-roofer-crane-operator-jobs.jpg')
        ->and($this->blog->content)->toContain('scaffolder-roofer-crane-operator-jobs-lift.jpg')
        ->and($this->blog->content)->toContain('scaffolder-roofer-crane-operator-jobs-steel.jpg')
        ->and($this->blog->content)->not->toContain('scaffolder-roofer-crane-operator-jobs.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    expect(app(StructuredDataService::class)->faqsFromHtml($this->blog->content))->toHaveCount(8);

    $pasf = substr($this->blog->content, (int) strpos($this->blog->content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('links only internally and drops the job-board material', function () {
    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $this->blog->content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    foreach (['seek', 'scaffjobs', '6aa10dd5', '24.57', '39.27', '120,000', 'roofforce'] as $banned) {
        expect(strtolower($this->blog->content))->not->toContain($banned);
    }
});

it('renders', function () {
    get('/blog/'.SCAFFOLDER_SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('states the official facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'ncs pay' => '£25,000 for starters and £51,000 for experienced scaffolders',
    'cisrs' => 'Construction Industry Scaffolders Record Scheme',
    'not on tsl' => 'Scaffolders, stagers and riggers (SOC 8151) are not on it',
    'roofers not on tsl' => 'neither are roofers (SOC 5313)',
    'supervisor rate' => '£41,800',
    'crane median' => 'C$42.77',
    'alberta' => 'C$45.00',
    'ontario 339a' => 'Branch 1 (339A)',
    'high risk work' => 'high risk work licence',
]);

it('creates three listings with official apply URLs, no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->advertiser->name)->toContain('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uk' => ['Scaffolder — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'canada' => ['Crane Operator — Canadian', 'https://www.jobbank.gc.ca/jobsearch', 'Canada'],
    'australia' => ['Roofer and Roof Tiler — Australian', 'https://www.workforceaustralia.gov.au/individuals/jobs/search', 'Australia'],
]);

it('cross-links with the construction trades guides in both directions', function (string $seeder, string $slug) {
    $this->seed($seeder);

    expect($this->blog->content)->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.SCAFFOLDER_SLUG);
})->with([
    'supervisor' => [SiteSupervisorForemanAbroadBlogSeeder::class, 'how-to-become-a-site-supervisor-or-foreman-abroad'],
    'tiler' => [TilerPlastererMasonJobsOverseasBlogSeeder::class, 'tiler-plasterer-and-mason-jobs-overseas'],
    'carpenter' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'carpenter-jobs-in-the-uk-canada-and-australia'],
]);
