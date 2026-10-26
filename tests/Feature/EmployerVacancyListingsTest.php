<?php

use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EmployerVacancyListingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates the Hays Electrical Services data entry vacancy as a real employer listing', function () {
    $this->seed(EmployerVacancyListingsSeeder::class);
    $this->seed(EmployerVacancyListingsSeeder::class);

    $jobs = Job::where('position', EmployerVacancyListingsSeeder::HAYS_POSITION)->get();

    expect($jobs)->toHaveCount(1);

    $job = $jobs->first();

    expect($job->listing_type)->toBe(Job::LISTING_VACANCY)
        ->and($job->advertiser->name)->toBe('Hays Electrical Services')
        ->and($job->application_url)->toBe(EmployerVacancyListingsSeeder::HAYS_URL)
        ->and($job->location->name)->toBe('Houston')
        ->and($job->location->area)->toBe('Texas')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->description)->toContain('not by JobGader')
        ->and($job->description)->toContain('22 October 2026')
        ->and(strtolower($job->description))->not->toContain('apply now')
        ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160)
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeTrue();
});
