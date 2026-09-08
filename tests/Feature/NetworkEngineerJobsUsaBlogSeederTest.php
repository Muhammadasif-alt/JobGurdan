<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\NetworkEngineerJobsUsaBlogSeeder;

const NETWORK_ENGINEER_SLUG = 'network-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(NetworkEngineerJobsUsaBlogSeeder::class);
});

it('publishes the network engineer guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', NETWORK_ENGINEER_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/network-engineer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('network-engineer-jobs-in-usa-operations.jpg')
        ->and($blog->content)->toContain('network-engineer-jobs-in-usa-cloud.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.NETWORK_ENGINEER_SLUG)
        ->assertOk()
        ->assertSee('Network Engineer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-network-engineer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', NETWORK_ENGINEER_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('CCNA jobs USA');

    $this->get('/blog/'.NETWORK_ENGINEER_SLUG)->assertSee('"FAQPage"', false);
});

it('splits the title across the two occupations moving opposite ways', function () {
    // The draft says demand remains strong. That describes the architect
    // half; most readers are standing in the administrator half, which is
    // projected to shrink under the same job title.
    $content = Blog::where('slug', NETWORK_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('$99,130')
        ->toContain('$134,050')
        ->toContain('decline 4 per cent from 2025 to 2035')
        ->toContain('grow 8 per cent')
        ->toContain('$34,920');
});

it('moves automation off the bottom of the skills list', function () {
    // In a shrinking operations occupation, automation is what decides
    // whether the role contracts or advances — so it gets a section.
    $content = Blog::where('slug', NETWORK_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('Automation Is Not the Seventh Skill')
        ->toContain('separates the engineer whose role contracts');
});

it('gives the architect ladder the timeline BLS states', function () {
    $content = Blog::where('slug', NETWORK_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('five years or more of experience in a related occupation');
});

it('warns that federal postings need citizenship and a sponsor', function () {
    $content = Blog::where('slug', NETWORK_ENGINEER_SLUG)->value('content');

    expect($content)->toContain('US citizenship')
        ->toContain('employer to sponsor it');

    expect(Job::where('position', 'like', 'Network Engineer%')->value('description'))
        ->toContain('security clearance');
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Network Engineer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-network-engineer-jobs.html')
        // Two occupations $34,920 apart, so a single band would be invented.
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
});

it('is linked back from the cloud and security engineering guides', function () {
    $this->seed(Database\Seeders\CloudEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\CybersecurityEngineerJobsUsaBlogSeeder::class);

    foreach (['cloud-engineer-jobs-in-usa', 'cybersecurity-engineer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.NETWORK_ENGINEER_SLUG);
    }
});
