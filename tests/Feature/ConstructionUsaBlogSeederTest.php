<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\ConstructionUsaBlogSeeder;

const CONSTRUCTION_APPLY_URL = 'https://www.indeed.com/jobs?q=construction+visa+sponsorship';

beforeEach(function () {
    $this->seed(ConstructionUsaBlogSeeder::class);
});

it('publishes the construction blog post with its images and SEO fields', function () {
    $blog = Blog::where('slug', 'construction-jobs-in-usa-for-foreigners')->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/construction-jobs-in-usa-for-foreigners.jpg')
        ->and($blog->content)->toContain('construction-jobs-in-usa-for-foreigners-site.jpg')
        ->and($blog->meta_title)->not->toBeEmpty()
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/construction-jobs-in-usa-for-foreigners')
        ->assertOk()
        ->assertSee('Construction Jobs in USA for Foreigners')
        ->assertSee('Visa Pathways for Construction Workers')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/caregiver-jobs-in-uk-with-visa-sponsorship', false)
        ->assertSee(CONSTRUCTION_APPLY_URL, false);
});

it('creates the matching construction listing', function () {
    $job = Job::where('position', 'like', 'Construction Worker%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(CONSTRUCTION_APPLY_URL)
        ->and($job->category->slug)->toBe('construction-trades')
        ->and($job->salary_minimum)->toEqual(37000)
        ->and($job->location->name)->toBe('United States');
});

it('serves the job page without JobPosting markup', function () {
    $response = $this->get('/jobs/construction-worker-skilled-trades-visa-sponsorship-available-united-states');

    $response->assertOk()->assertSee(CONSTRUCTION_APPLY_URL, false);

    // Apply hands off to a job board, so JobPosting markup is deliberately omitted.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(ConstructionUsaBlogSeeder::class);

    expect(Blog::where('slug', 'construction-jobs-in-usa-for-foreigners')->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Construction Worker%')->count())->toBe(1);
});
