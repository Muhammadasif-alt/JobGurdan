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
