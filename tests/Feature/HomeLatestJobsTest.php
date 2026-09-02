<?php

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Support\Facades\Cache;

/**
 * @param  non-empty-string  $position
 */
function makeHomepageJob(string $position): Job
{
    $advertiser = Advertiser::firstOrCreate(
        ['name' => 'Homepage Test Employer'],
        ['type' => 'Agency', 'display_reference' => 'homepage-test-employer']
    );

    $location = Location::firstOrCreate(
        ['name' => 'United States'],
        ['area' => 'Nationwide', 'country' => 'United States']
    );

    $category = Category::firstOrCreate(
        ['slug' => 'hospitality-tourism'],
        ['name' => 'Hospitality & Tourism']
    );

    return Job::create([
        'advertiser_id' => $advertiser->id,
        'location_id' => $location->id,
        'category_id' => $category->id,
        'position' => $position,
        'description' => '<p>Test opening.</p>',
        'employment_type' => 'Full-time',
    ]);
}

beforeEach(function () {
    Cache::flush();
});

it('shows the latest jobs grid under the hero', function () {
    makeHomepageJob('Room Attendant');

    $this->get('/')
        ->assertOk()
        ->assertSee('home-jobs-section')
        ->assertSee('Room Attendant')
        ->assertSee('View All Jobs');
});

it('caps the homepage grid at eight jobs', function () {
    foreach (range(1, 11) as $n) {
        makeHomepageJob("Front Desk Agent {$n}");
    }

    $response = $this->get('/');

    $response->assertOk();
    expect(substr_count($response->getContent(), 'class="home-job-card"'))->toBe(8);
});

it('shows the newest jobs first and drops the oldest', function () {
    makeHomepageJob('Oldest Opening');
    foreach (range(1, 8) as $n) {
        makeHomepageJob("Newer Opening {$n}");
    }

    $this->get('/')
        ->assertOk()
        ->assertSee('Newer Opening 8')
        ->assertDontSee('Oldest Opening');
});

it('links the grid button through to the jobs board', function () {
    makeHomepageJob('Breakfast Attendant');

    $this->get('/')
        ->assertOk()
        ->assertSee(route('jobs.index'), false);
});

it('hides the section entirely when no jobs exist', function () {
    $this->get('/')
        ->assertOk()
        ->assertDontSee('home-jobs-section');
});
