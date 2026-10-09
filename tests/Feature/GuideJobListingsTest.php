<?php

use App\Models\Job;
use Database\Seeders\GuideJobListingsSeeder;

it('creates the truck driver, caregiver and UK warehouse job listings', function () {
    $this->seed(GuideJobListingsSeeder::class);

    $jobs = Job::whereIn('position', [
        'Truck Driver — US Employers (CDL Roles)',
        'Caregiver — US Employers (EB-3 Sponsorship Route)',
        'Warehouse Operative — UK Employers',
    ])->get();

    expect($jobs)->toHaveCount(3);

    foreach ($jobs as $job) {
        expect($job->application_url)->toStartWith('https://')
            ->and($job->salary_minimum)->toBeNull()
            ->and($job->description)->not->toContain('Apply Now')
            ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160);
    }
});

it('gives each salary and from-Pakistan guide its own job listing', function () {
    $this->seed(GuideJobListingsSeeder::class);

    $positions = [
        'Hotel Housekeeper — US Hotels (Wage Check on H-2B Job Orders)',
        'Hotel Housekeeper — US Hotels (Applying From Pakistan)',
        'Warehouse Worker — US Employers (Wage Check on H-2B Job Orders)',
        'Warehouse Worker — US Employers (Applying From Pakistan)',
        'Landscaper — US Employers (Wage Check on H-2B Job Orders)',
        'Warehouse Operative — UK Employers (Pay Check)',
        'Landscaper — US Employers (Applying From Pakistan)',
        'Farm Worker — US Farms (Wage Check on H-2A Job Orders)',
        'Farm Worker — US Farms (Applying From Pakistan)',
    ];

    foreach ($positions as $position) {
        $job = Job::where('position', $position)->firstOrFail();

        expect($job->application_url)->toStartWith('https://')
            ->and($job->salary_minimum)->toBeNull()
            ->and($job->description)->not->toContain('Apply Now')
            ->and(strlen($job->meta_description))->toBeLessThanOrEqual(160);
    }
});

it('does not duplicate the listings when re-seeded', function () {
    $this->seed(GuideJobListingsSeeder::class);
    $this->seed(GuideJobListingsSeeder::class);

    expect(Job::where('position', 'Warehouse Operative — UK Employers')->count())->toBe(1);
});
