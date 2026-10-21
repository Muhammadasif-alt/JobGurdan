<?php

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;

beforeEach(function () {
    $location = Location::firstOrCreate(['name' => 'United States'], ['area' => 'Nationwide', 'country' => 'United States']);
    $category = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
    $employer = Advertiser::create(['name' => 'Acme Engineering', 'type' => 'Private', 'display_reference' => 'acme-engineering']);
    $portal = Advertiser::create(['name' => 'US City and Public Employers (Aggregated)', 'type' => 'Private', 'display_reference' => 'usa-public-aggregated']);

    $make = fn (Advertiser $advertiser, string $position): Job => Job::create([
        'advertiser_id' => $advertiser->id,
        'location_id' => $location->id,
        'category_id' => $category->id,
        'position' => $position,
        'description' => '<p>Test listing.</p>',
        'employment_type' => 'Full-time',
        'application_url' => 'https://example.com/jobs',
    ]);

    $this->jobs = ['vacancy' => $make($employer, 'Platform Engineer'), 'resource' => $make($portal, 'Job Seeker - City Job Board')];
});

it('keeps old jobs that have no expiry, whatever their age', function () {
    Job::query()->update(['created_at' => now()->subMonths(4), 'updated_at' => now()->subMonths(4)]);

    $this->artisan('jobs:expire-old')->assertSuccessful();

    expect(Job::count())->toBe(2)
        ->and(Job::active()->count())->toBe(2);
});

it('deletes jobs that were marked expired more than a week ago', function () {
    Job::query()->whereKey($this->jobs['vacancy']->id)->update(['status' => 'expired', 'updated_at' => now()->subDays(8)]);

    $this->artisan('jobs:expire-old')->assertSuccessful();

    expect(Job::query()->whereKey($this->jobs['vacancy']->id)->exists())->toBeFalse()
        ->and(Job::query()->whereKey($this->jobs['resource']->id)->exists())->toBeTrue();
});

it('keeps a job that was only just marked expired', function () {
    Job::query()->whereKey($this->jobs['vacancy']->id)->update(['status' => 'expired', 'updated_at' => now()->subDay()]);

    $this->artisan('jobs:expire-old')->assertSuccessful();

    expect(Job::query()->whereKey($this->jobs['vacancy']->id)->exists())->toBeTrue();
});

it('only ages jobs out when asked to with --expire-days', function () {
    Job::query()->update(['created_at' => now()->subDays(40)]);

    $this->artisan('jobs:expire-old', ['--expire-days' => 30, '--delete-days' => 60])->assertSuccessful();

    expect(Job::active()->count())->toBe(0);
});
