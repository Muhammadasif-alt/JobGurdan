<?php

use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AssistantProfessorJobsUsBlogSeeder;
use Database\Seeders\CollegesHiringProfessorsUsBlogSeeder;
use Database\Seeders\FacultyCareersUsBlogSeeder;
use Database\Seeders\HigherEducationDegreeJobsUsBlogSeeder;
use Database\Seeders\ProfessorEmploymentUsBlogSeeder;
use Database\Seeders\UkJobsWithVisaSponsorshipBlogSeeder;
use Database\Seeders\VacanciesInLondonBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

dataset('new guide listings', [
    'london vacancies' => [VacanciesInLondonBlogSeeder::class, 'Vacancies in London —', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'uk sponsorship' => [UkJobsWithVisaSponsorshipBlogSeeder::class, 'Sponsored Jobs with UK Licensed Sponsors', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'faculty careers' => [FacultyCareersUsBlogSeeder::class, 'Faculty Appointments at US Colleges', 'https://www.usa.gov/job-search', 'United States'],
    'professor employment' => [ProfessorEmploymentUsBlogSeeder::class, 'Professor Posts at US Universities', 'https://www.usa.gov/job-search', 'United States'],
    'assistant professor' => [AssistantProfessorJobsUsBlogSeeder::class, 'Assistant Professor Vacancies at US Universities', 'https://www.usa.gov/job-search', 'United States'],
    'colleges hiring' => [CollegesHiringProfessorsUsBlogSeeder::class, 'College Teaching Vacancies Across US Institutions', 'https://www.usa.gov/job-search', 'United States'],
    'higher education admin' => [HigherEducationDegreeJobsUsBlogSeeder::class, 'University Administration, Advising and Student Affairs', 'https://www.usa.gov/job-search', 'United States'],
]);

it('creates a listing that applies through an official service and quotes no salary', function (string $seeder, string $position, string $applyUrl, string $country) {
    $this->seed($seeder);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull("no listing created for {$position}")
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->salary_period)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->description)->toContain('not by JobGader');
})->with('new guide listings');

it('keeps JobPosting markup off listings that describe no single vacancy', function (string $seeder, string $position) {
    $this->seed($seeder);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with('new guide listings');

it('marks every one of these advertisers as aggregated', function (string $seeder, string $position) {
    $this->seed($seeder);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job->advertiser->name)->toContain('(Aggregated)');
})->with('new guide listings');

it('links to no aggregator from a listing description', function (string $seeder, string $position) {
    $this->seed($seeder);

    $job = Job::where('position', 'like', $position.'%')->first();

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) $job->description, $matches);

    expect($job->description)->not->toBeEmpty();

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'higheredjobs', 'monster', 'totaljobs', 'reed'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
})->with('new guide listings');
