<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\PlumberJobsAustraliaBlogSeeder;
use Database\Seeders\PlumberJobsUaeSaudiBlogSeeder;
use Database\Seeders\PlumberSalaryUkUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const PLUMBER_PAY_SLUG = 'plumber-salary-in-the-uk-and-usa';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    $blog = Blog::where('slug', PLUMBER_PAY_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/plumber-salary-uk-and-usa.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain('plumber-salary-uk-and-usa-compare.jpg')
        ->and($blog->content)->toContain('plumber-salary-uk-and-usa-worksite.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    $content = Blog::where('slug', PLUMBER_PAY_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('carries the official figures and not the draft job-board ones', function (string $claim) {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_PAY_SLUG)->value('content'))->toContain($claim);
})->with([
    // BLS Occupational Outlook Handbook, plumbers, pipefitters and steamfitters.
    'us median' => '$63,800',
    'us low tenth' => '$44,150',
    'us high tenth' => '$108,420',
    'us employment' => '510,600',
    'us growth' => '+7 per cent, +34,500 jobs',
    'us openings' => 'about 42,000',
    'government median' => '$71,660',
    'contractor median' => '$63,010',
    // ONS, April 2025, all full-time employees.
    'uk benchmark' => '£39,039',
    'uk prior year' => '£37,439',
    // The legal line that actually moves UK pay.
    'gas regulations' => 'Gas Safety (Installation and Use) Regulations 1998',
    'gas register' => 'Gas Safe Register',
    'plumbing unlicensed uk' => 'Plumbing is not a licensed trade in the United Kingdom',
    // US licensing does not travel.
    'no national licence' => 'There is no national plumbing licence',
]);

it('flags the superseded projection round rather than repeating it', function () {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_PAY_SLUG)->value('content'))
        ->toContain('it is quoting the previous projection round');
});

it('says plainly that no ONS plumber median is published', function () {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_PAY_SLUG)->value('content'))
        ->toContain('does not publish an easily reachable median for plumbers');
});

it('republishes none of the draft aggregator or employer pay claims', function (string $figure) {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_PAY_SLUG)->value('content'))->not->toContain($figure);
})->with([
    'indeed uk average' => '36,339',
    'indeed us hourly' => '30.77',
    'unsourced ons median' => '37,881',
    'roto rooter claim' => '130,000',
    'pimlico claim' => 'up to £100,000',
]);

it('links to no aggregator', function () {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', PLUMBER_PAY_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'monster', 'totaljobs', 'reed', 'seek'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    get('/blog/'.PLUMBER_PAY_SLUG)
        ->assertOk()
        ->assertSee('Plumber Salary in the UK and USA')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('creates one listing per country with no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull("no listing created for {$position}")
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->advertiser->name)->toContain('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uk' => ['Plumber and Heating Engineer — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'us' => ['Plumber, Pipefitter and Steamfitter — US', 'https://www.usa.gov/job-search', 'United States'],
]);

it('cross-links with the rest of the plumbing cluster in both directions', function (string $seeder, string $slug) {
    $this->seed(PlumberSalaryUkUsaBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', PLUMBER_PAY_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.PLUMBER_PAY_SLUG);
})->with([
    'gulf plumber' => [PlumberJobsUaeSaudiBlogSeeder::class, 'plumber-jobs-in-uae-and-saudi-arabia'],
    'australia plumber' => [PlumberJobsAustraliaBlogSeeder::class, 'plumber-jobs-in-australia'],
]);
