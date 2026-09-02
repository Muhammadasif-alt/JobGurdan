<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\HotelJobsBlogSeeder;

const HOTEL_APPLY_URL = 'https://www.indeed.com/q-usa-hotel-jobs-with-visa-sponsorship-jobs.html?vjk=211f8bcb51a8380f';

beforeEach(function () {
    $this->seed(HotelJobsBlogSeeder::class);
});

it('publishes the hotel jobs blog post', function () {
    $blog = Blog::where('slug', 'hotel-jobs-in-usa-for-foreigners')->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->reading_time)->toBeGreaterThan(0)
        ->and($blog->category->slug)->toBe('visa-sponsorship');
});

it('renders the blog post with its apply links intact', function () {
    $this->get('/blog/hotel-jobs-in-usa-for-foreigners')
        ->assertOk()
        ->assertSee('Hotel Jobs in USA for Foreigners')
        ->assertSee('Why Hotels Sponsor Foreign Workers')
        ->assertSee(HOTEL_APPLY_URL, false);
});

it('creates the matching hotel job pointing at the same apply url', function () {
    $job = Job::where('position', 'like', 'Hotel Staff%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(HOTEL_APPLY_URL)
        ->and($job->category->slug)->toBe('hospitality-tourism')
        ->and($job->location->name)->toBe('United States');
});

it('serves the hotel job page without JobPosting markup', function () {
    $response = $this->get('/jobs/hotel-staff-housekeeping-front-desk-kitchen-visa-sponsorship-available-united-states');

    $response->assertOk()->assertSee(HOTEL_APPLY_URL, false);

    // Apply hands off to a job board, so JobPosting markup is deliberately omitted.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(HotelJobsBlogSeeder::class);

    expect(Blog::where('slug', 'hotel-jobs-in-usa-for-foreigners')->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Hotel Staff%')->count())->toBe(1);
});
