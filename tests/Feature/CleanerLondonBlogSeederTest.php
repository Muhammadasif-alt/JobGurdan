<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CleanerLondonBlogSeeder;

const CLEANER_APPLY_URL = 'https://uk.indeed.com/q-cleaning-l-london-jobs.html?vjk=145bc3777d84d4f3';

beforeEach(function () {
    $this->seed(CleanerLondonBlogSeeder::class);
});

it('publishes the cleaner blog post with its images and SEO fields', function () {
    $blog = Blog::where('slug', 'cleaner-jobs-in-london-no-experience-needed')->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/cleaner-jobs-in-london.jpg')
        ->and($blog->content)->toContain('cleaner-jobs-in-london-office.jpg')
        ->and($blog->meta_title)->not->toBeEmpty()
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/cleaner-jobs-in-london-no-experience-needed')
        ->assertOk()
        ->assertSee('Cleaner Jobs in London')
        ->assertSee('Types of Cleaner Jobs in London')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/construction-jobs-in-usa-for-foreigners', false)
        ->assertSee(CLEANER_APPLY_URL, false);
});

it('creates a London listing that does not promise sponsorship', function () {
    $job = Job::where('position', 'like', 'Cleaner%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(CLEANER_APPLY_URL)
        ->and($job->category->slug)->toBe('cleaning-facilities')
        ->and($job->salary_currency)->toBe('GBP')
        ->and($job->location->name)->toBe('London')
        ->and($job->description)->toContain('cannot be sponsored');
});

it('prices cleaner pay against the 2026 legal minimum, not below it', function () {
    $blog = Blog::where('slug', 'cleaner-jobs-in-london-no-experience-needed')->first();
    $job = Job::where('position', 'like', 'Cleaner%')->first();

    // The London Living Wage is £14.80, and £9 to £11 an hour or £19,000 a
    // year full-time is below the £12.71 National Living Wage at 21 or over.
    expect($blog->content)->toContain('&pound;12.71')
        ->toContain('&pound;14.80')
        ->toContain('&pound;24,784.50')
        ->not->toContain('&pound;13.85')
        ->not->toContain('&pound;9&ndash;&pound;11')
        ->not->toContain('&pound;19,000&ndash;&pound;26,000');

    expect($job->description)->toContain('&pound;12.71')
        ->not->toContain('&pound;13.85')
        ->and((float) $job->salary_minimum)->toBe(12.71);
});

it('serves the job page without JobPosting markup', function () {
    $response = $this->get('/jobs/cleaner-london-no-experience-needed-london');

    $response->assertOk()->assertSee(CLEANER_APPLY_URL, false);

    // Apply hands off to a job board, so JobPosting markup is deliberately omitted.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(CleanerLondonBlogSeeder::class);

    expect(Blog::where('slug', 'cleaner-jobs-in-london-no-experience-needed')->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Cleaner%')->count())->toBe(1);
});
