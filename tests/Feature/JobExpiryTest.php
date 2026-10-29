<?php

use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EmployerVacancyListingsSeeder;
use Illuminate\Support\Carbon;

/**
 * Vacancies carry a closing date (jobs.expires_at). Before 29 October 2026 the
 * column did not exist, so no JobPosting could declare validThrough and closed
 * adverts kept their markup.
 */
afterEach(function () {
    Carbon::setTestNow();
});

it('gives the dated vacancies a validThrough and the CBSA notice its published salary', function () {
    Carbon::setTestNow('2026-10-29 12:00:00');
    $this->seed(EmployerVacancyListingsSeeder::class);

    $toronto = Job::where('position', EmployerVacancyListingsSeeder::TORONTO_PARAMEDIC_POSITION)->firstOrFail();
    $cbsa = Job::where('position', EmployerVacancyListingsSeeder::CBSA_POSITION)->firstOrFail();

    $service = app(StructuredDataService::class);
    $torontoPosting = $service->jobPosting($toronto, 'https://jobgader.com/jobs/toronto');
    $cbsaPosting = $service->jobPosting($cbsa, 'https://jobgader.com/jobs/cbsa');

    expect($torontoPosting['validThrough'])->toStartWith('2026-11-19T04:59:59')
        ->and($cbsaPosting['validThrough'])->toStartWith('2027-06-25T06:59:59')
        ->and($cbsaPosting['baseSalary']['currency'])->toBe('CAD')
        ->and($cbsaPosting['baseSalary']['value']['minValue'])->toEqual(80344)
        ->and($cbsaPosting['baseSalary']['value']['maxValue'])->toEqual(89462)
        ->and($cbsaPosting['baseSalary']['value']['unitText'])->toBe('YEAR')
        ->and($service->describesSingleVacancy($toronto->application_url, $toronto))->toBeTrue();
});

it('drops the JobPosting markup and expires the job once the closing date passes', function () {
    Carbon::setTestNow('2026-10-29 12:00:00');
    $this->seed(EmployerVacancyListingsSeeder::class);

    Carbon::setTestNow('2026-11-20 09:00:00');
    $toronto = Job::where('position', EmployerVacancyListingsSeeder::TORONTO_PARAMEDIC_POSITION)->firstOrFail();

    expect(app(StructuredDataService::class)->describesSingleVacancy($toronto->application_url, $toronto))->toBeFalse();

    $this->artisan('jobs:expire-old', ['--skip-delete' => true])->assertSuccessful();

    expect($toronto->fresh()->status)->toBe('expired')
        ->and(Job::where('position', EmployerVacancyListingsSeeder::CBSA_POSITION)->firstOrFail()->status)->not->toBe('expired');
});
