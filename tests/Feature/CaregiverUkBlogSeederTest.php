<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CaregiverUkBlogSeeder;

const CARE_APPLY_URL = 'https://uk.indeed.com/jobs?q=care+assistant';

beforeEach(function () {
    $this->seed(CaregiverUkBlogSeeder::class);
});

it('publishes the caregiver blog post with its images', function () {
    $blog = Blog::where('slug', 'caregiver-jobs-in-uk-with-visa-sponsorship')->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/caregiver-jobs-in-uk-visa-sponsorship.jpg')
        ->and($blog->content)->toContain('caregiver-jobs-in-uk-visa-sponsorship-carer.jpg')
        ->and($blog->meta_description)->not->toBeEmpty();
});

it('renders the post and links to the other sponsorship guides', function () {
    $this->get('/blog/caregiver-jobs-in-uk-with-visa-sponsorship')
        ->assertOk()
        ->assertSee('Caregiver Jobs in UK with Visa Sponsorship')
        ->assertSee('What Actually Changed')
        ->assertSee('/blog/hotel-jobs-in-usa-for-foreigners', false)
        ->assertSee(CARE_APPLY_URL, false);
});

it('creates a listing that does not promise closed sponsorship', function () {
    $job = Job::where('position', 'like', 'Care Assistant%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(CARE_APPLY_URL)
        ->and($job->salary_currency)->toBe('GBP')
        ->and($job->location->name)->toBe('United Kingdom')
        ->and($job->description)->toContain('already hold the right to work in the UK');
});

it('serves the job page without JobPosting markup', function () {
    $response = $this->get('/jobs/care-assistant-support-worker-united-kingdom-united-kingdom');

    $response->assertOk()->assertSee(CARE_APPLY_URL, false);

    // Apply hands off to a job board, so JobPosting markup is deliberately omitted.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(CaregiverUkBlogSeeder::class);

    expect(Blog::where('slug', 'caregiver-jobs-in-uk-with-visa-sponsorship')->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Care Assistant%')->count())->toBe(1);
});
