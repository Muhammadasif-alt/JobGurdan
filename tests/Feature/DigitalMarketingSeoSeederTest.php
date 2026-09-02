<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\DigitalMarketingSeoSeeder;

const SEO_APPLY_URL = 'https://pk.indeed.com/viewjob?jk=4670dbdb8daeb9c1';
const SEO_BLOG_SLUG = 'digital-marketing-expert-seo-job-at-urban-solar-remote-pakistan';
const SEO_JOB_SLUG = 'digital-marketing-expert-seo-pakistan';

beforeEach(function () {
    $this->seed(DigitalMarketingSeoSeeder::class);
});

it('publishes the Urban Solar spotlight with its images and SEO fields', function () {
    $blog = Blog::where('slug', SEO_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->category->slug)->toBe('job-spotlights')
        ->and($blog->featured_image)->toBe('blogs/digital-marketing-expert-seo-pakistan.jpg')
        ->and($blog->content)->toContain('digital-marketing-expert-seo-pakistan-desk.jpg')
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/'.SEO_BLOG_SLUG)
        ->assertOk()
        ->assertSee('Urban Solar')
        ->assertSee('What the Job Actually Involves')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern', false)
        ->assertSee(SEO_APPLY_URL, false);
});

it('creates the remote Pakistan listing with PKR monthly pay', function () {
    $job = Job::where('position', 'Digital Marketing Expert (SEO)')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(SEO_APPLY_URL)
        ->and($job->salary_currency)->toBe('PKR')
        ->and($job->salary_minimum)->toEqual(80000)
        ->and($job->job_type)->toBe('Remote')
        ->and($job->advertiser->name)->toBe('Urban Solar Pvt Ltd.');
});

it('keeps JobPosting markup for this single-posting apply link', function () {
    $response = $this->get('/jobs/'.SEO_JOB_SLUG);

    $response->assertOk()->assertSee(SEO_APPLY_URL, false);

    expect($response->getContent())->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(DigitalMarketingSeoSeeder::class);

    expect(Blog::where('slug', SEO_BLOG_SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'Digital Marketing Expert (SEO)')->count())->toBe(1);
});
