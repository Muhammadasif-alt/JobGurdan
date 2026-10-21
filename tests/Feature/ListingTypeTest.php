<?php

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Support\Facades\Cache;

/**
 * @return array{vacancy: Job, resource: Job}
 */
function makeVacancyAndResource(): array
{
    $location = Location::firstOrCreate(
        ['name' => 'United States'],
        ['area' => 'Nationwide', 'country' => 'United States']
    );
    $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);

    $employer = Advertiser::create(['name' => 'Acme Engineering', 'type' => 'Private', 'display_reference' => 'acme-engineering']);
    $portal = Advertiser::create(['name' => 'US City and Public Employers (Aggregated)', 'type' => 'Private', 'display_reference' => 'usa-public-aggregated']);

    $vacancy = Job::create([
        'advertiser_id' => $employer->id,
        'location_id' => $location->id,
        'category_id' => $category->id,
        'position' => 'Platform Engineer',
        'description' => '<p>A real opening.</p>',
        'employment_type' => 'Full-time',
        'application_url' => 'https://example.com/careers/platform-engineer',
    ]);

    $resource = Job::create([
        'advertiser_id' => $portal->id,
        'location_id' => $location->id,
        'category_id' => $category->id,
        'position' => 'Job Seeker - City Job Board',
        'description' => '<p>An official portal.</p>',
        'employment_type' => 'Full-time',
        'application_url' => 'https://www.example.gov/jobs',
    ]);

    return ['vacancy' => $vacancy, 'resource' => $resource];
}

beforeEach(function () {
    Cache::flush();
});

it('marks jobs under an aggregated company as resources and the rest as vacancies', function () {
    $jobs = makeVacancyAndResource();

    expect($jobs['vacancy']->fresh()->listing_type)->toBe(Job::LISTING_VACANCY)
        ->and($jobs['resource']->fresh()->listing_type)->toBe(Job::LISTING_RESOURCE)
        ->and($jobs['resource']->isResource())->toBeTrue()
        ->and(Job::vacancy()->pluck('position')->all())->toBe(['Platform Engineer'])
        ->and(Job::resource()->pluck('position')->all())->toBe(['Job Seeker - City Job Board']);
});

it('lists vacancies and labelled resources on /jobs, and filters them by type', function () {
    makeVacancyAndResource();

    $this->get('/jobs')
        ->assertOk()
        ->assertSee('Platform Engineer')
        ->assertSee('Job Seeker - City Job Board')
        ->assertSee('Official resource');

    $this->get('/jobs?type=vacancy')->assertOk()->assertSee('Platform Engineer')->assertDontSee('Job Seeker - City Job Board');
    $this->get('/jobs?type=resource')->assertOk()->assertSee('Job Seeker - City Job Board')->assertDontSee('Platform Engineer');
});

it('shows resources on the portals page and keeps that page out of search', function () {
    makeVacancyAndResource();

    $this->get('/job-portals')
        ->assertOk()
        ->assertSee('Job Portals &amp; Resources', false)
        ->assertSee('Job Seeker - City Job Board')
        ->assertDontSee('Platform Engineer')
        ->assertSee('content="noindex, follow"', false);
});

it('counts only vacancies and real employers in the job stats', function () {
    makeVacancyAndResource();

    $html = $this->get('/jobs')->assertOk()->getContent();
    preg_match('#<strong>([\d,]+)\+</strong>\s*<span>Active Jobs</span>#', $html, $jobs);
    preg_match('#<strong>([\d,]+)\+</strong>\s*<span>Employers</span>#', $html, $employers);

    expect($jobs[1])->toBe('1')->and($employers[1])->toBe('1');
});

it('shows the newest jobs on the homepage with resources labelled, and keeps resources out of the job sitemap', function () {
    makeVacancyAndResource();

    $this->get('/')->assertOk()->assertSee('Platform Engineer')->assertSee('Job Seeker - City Job Board')->assertSee('Official resource');

    $this->get('/sitemap-jobs-1.xml')
        ->assertOk()
        ->assertSee('platform-engineer-united-states')
        ->assertDontSee('job-seeker-city-job-board');
});

it('labels a resource page and keeps it out of the index', function () {
    makeVacancyAndResource();

    $this->get('/jobs/job-seeker-city-job-board-united-states')
        ->assertOk()
        ->assertSee('Official resource')
        ->assertSee('content="noindex, follow"', false);

    $this->get('/jobs/platform-engineer-united-states')
        ->assertOk()
        ->assertDontSee('Official resource')
        ->assertDontSee('content="noindex, follow"', false);
});

it('no longer makes trust claims the site cannot support', function () {
    makeVacancyAndResource();

    $pages = [
        $this->get('/jobs/platform-engineer-united-states')->getContent(),
        $this->get('/jobs')->getContent(),
        $this->get('/categories/it-software')->getContent(),
    ];

    foreach ($pages as $html) {
        expect($html)->not->toContain('trust &amp; safety team')
            ->not->toContain('Daily Fresh Listings')
            ->not->toContain('Apply in One Click')
            ->not->toContain('Trusted Nationwide')
            ->not->toContain('10 million')
            ->not->toContain('Save your resume once');
    }
});
