<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\NursingJobsAbroadForeignNursesBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(NursingJobsAbroadForeignNursesBlogSeeder::class);
    $this->blog = Blog::where('slug', NursingJobsAbroadForeignNursesBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/nursing-jobs-abroad-foreign-nurses.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/nursing-jobs-abroad-foreign-nurses.jpg')))->toBeTrue();
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    expect(app(StructuredDataService::class)->faqsFromHtml($this->blog->content))->toHaveCount(8);

    $pasf = substr($this->blog->content, (int) strpos($this->blog->content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('links only to our own pages and drops the brief artifacts', function () {
    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $this->blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $href) {
        expect($href)->toMatch('#^/(blog|categories)/#');
    }

    expect(strtolower($this->blog->content))->not->toContain('indeed')
        ->not->toContain('apply now')
        ->not->toContain('findability check');
});

it('states the verified registration and visa facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'scfhs' => 'Saudi Commission for Health Specialties (SCFHS)',
    'mumaris' => 'Mumaris+',
    'osce' => 'Objective Structured Clinical Examination (OSCE)',
    'visa salary' => 'at least GBP 25,000 or the going rate',
    'visa sponsor' => 'Certificate of Sponsorship',
    'nsw wording' => 'usually need at least 24 months of nursing or midwifery experience',
    'nsw is not national' => 'not a universal Australian registration rule',
]);

it('does not claim an unchecked start date for the Australian pathway', function () {
    expect($this->blog->content)->not->toContain('April 2025');
});

it('cross-links to existing guides and the healthcare category', function () {
    expect($this->blog->content)->toContain('/blog/nurse-jobs-in-saudi-arabia')
        ->toContain('/blog/nursing-assistant-jobs-in-the-uk')
        ->toContain('/categories/healthcare');
});

it('creates one listing per country with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('healthcare')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'saudi' => ['Registered Nurse — Saudi', 'https://www.kfshrc.edu.sa/en/home/careers', 'Saudi Arabia'],
    'uk' => ['Registered Nurse — NHS', 'https://www.jobs.nhs.uk/candidate/search/results?keyword=nurse', 'United Kingdom'],
    'australia' => ['Registered Nurse — Australian', 'https://www.health.nsw.gov.au/nursing/careers/Pages/overseas-recruitment.aspx', 'Australia'],
]);

it('renders the guide', function () {
    get('/blog/'.NursingJobsAbroadForeignNursesBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(NursingJobsAbroadForeignNursesBlogSeeder::class);

    expect(Blog::where('slug', NursingJobsAbroadForeignNursesBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Registered Nurse — %')->count())->toBe(3);
});
