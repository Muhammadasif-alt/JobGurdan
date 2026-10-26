<?php

use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EmployerVacancyListingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('employer vacancies', [
    'hays houston' => ['hays', 'Houston', 'Texas', '22 October 2026'],
    'pocketful delhi' => ['pocketful', 'Delhi', 'Delhi', '26 October 2026'],
    'ibex karachi' => ['ibex', 'Karachi', 'Sindh', '26 October 2026'],
    'accor dubai' => ['accor', 'Dubai', 'Dubai', '26 October 2026'],
]);

it('creates each employer vacancy as a real, dated, salary-free listing', function (string $key, string $city, string $area, string $checked) {
    $this->seed(EmployerVacancyListingsSeeder::class);
    $this->seed(EmployerVacancyListingsSeeder::class);

    $vacancy = EmployerVacancyListingsSeeder::vacancies()[$key];
    $jobs = Job::where('position', $vacancy['position'])->get();

    expect($jobs)->toHaveCount(1);

    $job = $jobs->first();

    expect($job->listing_type)->toBe(Job::LISTING_VACANCY)
        ->and($job->advertiser->name)->not->toContain('(Aggregated)')
        ->and($job->application_url)->toBe($vacancy['url'])
        ->and($job->location->name)->toBe($city)
        ->and($job->location->area)->toBe($area)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->description)->toContain('not by JobGader')
        ->and($job->description)->toContain($checked)
        ->and(strtolower($job->description))->not->toContain('apply now')
        ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160)
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeTrue();
})->with('employer vacancies');
