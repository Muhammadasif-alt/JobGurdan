<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\DevOpsEngineerJobsUsaBlogSeeder;

const DEVOPS_SLUG = 'devops-engineer-jobs-in-usa';

beforeEach(function () {
    $this->seed(DevOpsEngineerJobsUsaBlogSeeder::class);
});

it('publishes the devops guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', DEVOPS_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/devops-engineer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('devops-engineer-jobs-in-usa-pipeline.jpg')
        ->and($blog->content)->toContain('devops-engineer-jobs-in-usa-platform.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.DEVOPS_SLUG)
        ->assertOk()
        ->assertSee('DevOps Engineer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-devops-engineer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', DEVOPS_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('SRE jobs USA');

    $this->get('/blog/'.DEVOPS_SLUG)->assertSee('"FAQPage"', false);
});

it('explains the job-board outlier instead of repeating it as a salary', function () {
    // The draft quoted "$301,600 at Siemens" beside a named employer. That is
    // the highest figure anyone submitted to a self-reported dataset, not a
    // band, and repeating it implies a verification nobody performed.
    $content = Blog::where('slug', DEVOPS_SLUG)->value('content');

    expect($content)->toContain('built from self-reported submissions')
        ->toContain('It is the highest thing anyone typed in')
        // Named alongside its explanation, and never beside the employer.
        ->not->toContain('Siemens');

    // And the honest anchor is the occupation this work is counted under.
    expect($content)->toContain('$135,980')
        ->toContain('$82,460')
        ->toContain('$214,670');
});

it('gives the delivery measures a senior interview runs on', function () {
    // The draft's skills section is a tool list, which is the screening layer
    // rather than what separates shortlisted candidates.
    $content = Blog::where('slug', DEVOPS_SLUG)->value('content');

    expect($content)->toContain('Deployment frequency')
        ->toContain('Lead time for changes')
        ->toContain('Change failure rate')
        ->toContain('Time to restore service')
        ->toContain('Moving speed and stability in the same direction');
});

it('covers on-call, which the postings do not', function () {
    $content = Blog::where('slug', DEVOPS_SLUG)->value('content');

    expect($content)->toContain('How often is the rotation?')
        ->toContain('Is it paid?');

    expect(Job::where('position', 'like', 'DevOps Engineer%')->value('description'))
        ->toContain('Ask about the on-call rotation and whether it is paid');
});

it('states what TS/SCI actually requires', function () {
    $content = Blog::where('slug', DEVOPS_SLUG)->value('content');

    expect($content)->toContain('TS/SCI')
        ->toContain('full-scope background investigation')
        ->toContain('US citizenship');
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'DevOps Engineer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-devops-engineer-jobs.html')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
});

it('completes the infrastructure set in both directions', function () {
    $this->seed(Database\Seeders\CloudEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\NetworkEngineerJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder::class);

    foreach (['cloud-engineer-jobs-in-usa', 'network-engineer-jobs-in-usa', 'software-developer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.DEVOPS_SLUG);
    }

    $content = Blog::where('slug', DEVOPS_SLUG)->value('content');
    expect($content)->toContain('/blog/cloud-engineer-jobs-in-usa')
        ->toContain('/blog/network-engineer-jobs-in-usa')
        ->toContain('/blog/cybersecurity-engineer-jobs-in-usa');
});
