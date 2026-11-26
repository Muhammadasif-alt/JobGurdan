<?php

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use App\Services\StructuredDataService;
use Database\Seeders\OfficialVacanciesNov2026Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-11-26 12:00:00', 'Asia/Karachi'));
});

afterEach(function () {
    Carbon::setTestNow();
});

dataset('official vacancies', array_keys(OfficialVacanciesNov2026Seeder::vacancies()));

it('seeds each checked vacancy once as a real, indexable listing', function (string $key) {
    $this->seed(OfficialVacanciesNov2026Seeder::class);
    $this->seed(OfficialVacanciesNov2026Seeder::class);

    $vacancy = OfficialVacanciesNov2026Seeder::vacancies()[$key];
    $jobs = Job::where('position', $vacancy['position'])->get();

    expect($jobs)->toHaveCount(1);

    $job = $jobs->first();

    expect($job->listing_type)->toBe(Job::LISTING_VACANCY)
        ->and($job->isThin())->toBeFalse()
        ->and($job->advertiser->name)->not->toContain('(Aggregated)')
        ->and($job->application_url)->toBe($vacancy['url'])
        ->and($job->description)->toContain('not by JobGader')
        ->and(strtolower($job->description))->not->toContain('apply now')
        ->and($job->description)->not->toContain("\u{2014}")
        ->and(mb_strlen($job->meta_description))->toBeLessThanOrEqual(160)
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeTrue();

    if ($vacancy['closes'] === null) {
        expect($job->expires_at)->toBeNull();
    } else {
        expect($job->expires_at->copy()->setTimezone('Asia/Karachi')->toDateString())->toBe($vacancy['closes']);
    }
})->with('official vacancies');

it('stops describing a vacancy as open once its closing date has passed', function () {
    $this->seed(OfficialVacanciesNov2026Seeder::class);

    $job = Job::where('position', OfficialVacanciesNov2026Seeder::PPSC_POSITION)->firstOrFail();

    Carbon::setTestNow(Carbon::parse('2026-12-01 09:00:00', 'Asia/Karachi'));

    expect(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
});

it('shows real vacancies before portal round-ups on the homepage', function () {
    $category = Category::create(['name' => 'Test Trades', 'slug' => 'test-trades']);
    $location = Location::create(['name' => 'Testville', 'area' => 'Nationwide', 'country' => 'Testland']);
    $portal = Advertiser::create(['name' => 'Testland Employers (Aggregated)']);

    foreach (range(1, 8) as $n) {
        Job::create([
            'category_id' => $category->id,
            'location_id' => $location->id,
            'advertiser_id' => $portal->id,
            'position' => 'Portal Round-up '.$n,
            'description' => '<p>Portal search.</p>',
        ]);
    }

    $this->seed(OfficialVacanciesNov2026Seeder::class);

    foreach (range(9, 16) as $n) {
        Job::create([
            'category_id' => $category->id,
            'location_id' => $location->id,
            'advertiser_id' => $portal->id,
            'position' => 'Portal Round-up '.$n,
            'description' => '<p>Portal search.</p>',
        ]);
    }

    Cache::flush();

    $featured = $this->get('/')->assertOk()->viewData('featuredJobs');

    expect($featured)->toHaveCount(8)
        ->and($featured->every(fn (Job $job): bool => $job->listing_type === Job::LISTING_VACANCY))->toBeTrue();
});
