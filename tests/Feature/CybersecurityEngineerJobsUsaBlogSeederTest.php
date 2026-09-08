<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CybersecurityEngineerJobsUsaBlogSeeder;

const ENGINEER_BLOG_SLUG = 'cybersecurity-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(CybersecurityEngineerJobsUsaBlogSeeder::class);
});

it('publishes the engineering guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', ENGINEER_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/cybersecurity-engineer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('cybersecurity-engineer-jobs-in-usa-infrastructure.jpg')
        ->and($blog->content)->toContain('cybersecurity-engineer-jobs-in-usa-architecture.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.ENGINEER_BLOG_SLUG)
        ->assertOk()
        ->assertSee('Cybersecurity Engineer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-cybersecurity-engineer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', ENGINEER_BLOG_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Security engineer jobs USA');

    $this->get('/blog/'.ENGINEER_BLOG_SLUG)->assertSee('"FAQPage"', false);
});

it('says plainly that no federal figure exists for the title', function () {
    // Every engineer-specific "median" in circulation is a job-board
    // aggregate. BLS counts this work under information security analysts.
    $content = Blog::where('slug', ENGINEER_BLOG_SLUG)->value('content');

    expect($content)->toContain('there is no federal figure for "cybersecurity engineer"')
        ->toContain('$129,180')
        ->toContain('$75,090')
        ->toContain('$199,850');
});

it('corrects the draft on junior engineering roles and on CKA', function () {
    $content = Blog::where('slug', ENGINEER_BLOG_SLUG)->value('content');

    // Willingness to learn compliance is the GRC path, not engineering.
    expect($content)->toContain('you cannot harden infrastructure you have never built')
        // CKA is the administrator exam; CKS is the security one, and the
        // second cannot be booked without a current first.
        ->toContain('Certified Kubernetes Security Specialist')
        ->toContain('will not let you register for CKS without an active CKA');
});

it('keeps off the analyst guide\'s territory and links to it instead', function () {
    // The clearance rules and the CISSP/CISM ladder belong to the analyst
    // post. Restating them would set the two competing for one query.
    $content = Blog::where('slug', ENGINEER_BLOG_SLUG)->value('content');

    expect($content)->toContain('/blog/cybersecurity-analyst-jobs-in-usa')
        ->not->toContain('Associate of ISC2')
        ->not->toContain('management years can never be waived');
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Cybersecurity Engineer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-cybersecurity-engineer-jobs.html')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader')
        ->and($job->description)->toContain('security clearance');
});

it('carries no stray non-ASCII in the fields search engines index', function () {
    // A Cyrillic "е" in a tag list looks identical and matches nothing.
    $blog = Blog::where('slug', ENGINEER_BLOG_SLUG)->first();

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('is linked back from the analyst and software developer guides', function () {
    $this->seed(Database\Seeders\CybersecurityAnalystJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder::class);

    foreach (['cybersecurity-analyst-jobs-in-usa', 'software-developer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.ENGINEER_BLOG_SLUG);
    }
});
