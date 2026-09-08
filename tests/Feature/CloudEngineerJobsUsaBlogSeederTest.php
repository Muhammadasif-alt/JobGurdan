<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CloudEngineerJobsUsaBlogSeeder;

const CLOUD_ENGINEER_SLUG = 'cloud-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(CloudEngineerJobsUsaBlogSeeder::class);
});

it('publishes the cloud engineer guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', CLOUD_ENGINEER_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/cloud-engineer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('cloud-engineer-jobs-in-usa-platform.jpg')
        ->and($blog->content)->toContain('cloud-engineer-jobs-in-usa-cost.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.CLOUD_ENGINEER_SLUG)
        ->assertOk()
        ->assertSee('Cloud Engineer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-cloud-engineer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', CLOUD_ENGINEER_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('AWS cloud engineer jobs USA');

    $this->get('/blog/'.CLOUD_ENGINEER_SLUG)->assertSee('"FAQPage"', false);
});

it('names the three occupations instead of inventing one median', function () {
    // No federal figure exists for this title, so the guide gives the three
    // occupations the work is counted under and says which is which.
    $content = Blog::where('slug', CLOUD_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('has no "cloud engineer" occupation')
        ->toContain('$99,130')
        ->toContain('$134,050')
        ->toContain('$135,980');
});

it('reads the declining sysadmin projection as the signal it is', function () {
    // A 4% decline next to a growing field looks like bad news. It is the
    // same work being re-titled, which makes it a migration cue.
    $content = Blog::where('slug', CLOUD_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('decline 4 per cent from 2025 to 2035')
        ->toContain('the work is not disappearing')
        ->toContain('grow 8 per cent')
        ->toContain('$34,920');
});

it('corrects the draft on Terraform being the entry barrier', function () {
    $content = Blog::where('slug', CLOUD_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('Terraform is the easy part')
        ->toContain('second job rather than a first');
});

it('promotes cost from the bottom of the skills list', function () {
    // Listings put cost last; it is the only part of the job with a dollar
    // figure attached, which is why it is the one that gets people promoted.
    $content = Blog::where('slug', CLOUD_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('it is the only part of the job with a dollar figure attached');
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Cloud Engineer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-cloud-engineer-jobs.html')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader')
        ->and($job->description)->toContain('There is no federal figure for this title');
});

it('is linked back from the security engineering and developer guides', function () {
    $this->seed(Database\Seeders\CybersecurityEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder::class);

    foreach (['cybersecurity-engineer-jobs-in-usa', 'software-developer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.CLOUD_ENGINEER_SLUG);
    }
});
