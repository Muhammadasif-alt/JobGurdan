<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CleanerLondonBlogSeeder;
use Database\Seeders\FrontendDeveloperLahoreSeeder;

const ERS_APPLY_URL = 'https://pk.indeed.com/viewjob?jk=6775ed0027cf10b0';
const ERS_BLOG_SLUG = 'senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern';
const ERS_JOB_SLUG = 'senior-frontend-developer-react-nextjs-mern-lahore';

beforeEach(function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);
});

it('publishes the ERS Tech spotlight with its images and SEO fields', function () {
    $blog = Blog::where('slug', ERS_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->category->slug)->toBe('job-spotlights')
        ->and($blog->featured_image)->toBe('blogs/senior-frontend-developer-lahore.jpg')
        ->and($blog->content)->toContain('senior-frontend-developer-lahore-desk.jpg')
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/'.ERS_BLOG_SLUG)
        ->assertOk()
        ->assertSee('ERS Tech')
        ->assertSee('The Tech Stack They')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/cleaner-jobs-in-london-no-experience-needed', false)
        ->assertSee(ERS_APPLY_URL, false);
});

it('creates the Lahore listing with PKR monthly pay', function () {
    $job = Job::where('position', 'like', 'Senior Frontend Developer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(ERS_APPLY_URL)
        ->and($job->salary_currency)->toBe('PKR')
        ->and($job->salary_period)->toBe('Monthly')
        ->and($job->location->name)->toBe('Lahore')
        ->and($job->advertiser->name)->toBe('ERS Tech');
});

it('keeps JobPosting markup when apply points at one specific posting', function () {
    $response = $this->get('/jobs/'.ERS_JOB_SLUG);

    $response->assertOk()->assertSee(ERS_APPLY_URL, false);

    // The apply link is an Indeed permalink, so the page really is one vacancy.
    expect($response->getContent())->toContain('"JobPosting"');
});

it('still drops JobPosting markup when apply points at a board search', function () {
    $this->seed(CleanerLondonBlogSeeder::class);

    $response = $this->get('/jobs/cleaner-london-no-experience-needed-london');

    $response->assertOk();

    // uk.indeed.com/q-...-jobs.html?vjk=... is a search page, not a permalink.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);

    expect(Blog::where('slug', ERS_BLOG_SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Senior Frontend Developer%')->count())->toBe(1);
});
