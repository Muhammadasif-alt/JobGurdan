<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\MobileAppDeveloperJobsUsaBlogSeeder;

const MOBILE_BLOG_SLUG = 'mobile-app-developer-jobs-in-usa';

beforeEach(function () {
    $this->seed(MobileAppDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the mobile developer guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', MOBILE_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/mobile-app-developer-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('mobile-app-developer-jobs-in-usa-build.jpg')
        ->and($blog->content)->toContain('mobile-app-developer-jobs-in-usa-testing.jpg')
        ->and($blog->meta_title)->not->toBeEmpty()
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and($blog->reading_time)->toBeGreaterThan(4);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/'.MOBILE_BLOG_SLUG)
        ->assertOk()
        ->assertSee('Mobile App Developer Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/software-developer-jobs-in-usa', false)
        ->assertSee('/blog/web-developer-jobs-in-usa', false)
        ->assertSee('https://www.indeed.com/q-mobile-app-developer-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', MOBILE_BLOG_SLUG)->value('content'));

    // "People Also Search For" reuses the same h3/p shape, so the extractor
    // must stop at the end of the FAQ section rather than sweep it up.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('iOS developer jobs USA');

    $this->get('/blog/'.MOBILE_BLOG_SLUG)->assertSee('"FAQPage"', false);
});

it('anchors the pay on the software developer occupation, not a single listing', function () {
    // The draft priced the role from one Indeed posting. Mobile work is
    // counted by BLS under software developers, and that classification is
    // what tells a reader which band the title belongs to.
    $content = Blog::where('slug', MOBILE_BLOG_SLUG)->value('content');

    expect($content)->toContain('$135,980')
        ->toContain('$82,460')
        ->toContain('$214,670')
        // The comparison that makes the classification useful.
        ->toContain('$92,650')
        ->toContain('$48,100')
        ->toContain('$34,360');
});

it('states the Google Play testing gate that stands in front of a shipped app', function () {
    // Every guide says "ship an app and link to it" and none of them say a
    // personal Play account cannot reach production for a fortnight.
    $content = Blog::where('slug', MOBILE_BLOG_SLUG)->value('content');

    expect($content)->toContain('12 testers opted in continuously for 14 days')
        ->toContain('13 November 2023')
        ->toContain('D-U-N-S')
        ->toContain('$99 a year')
        ->toContain('$25, once');
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Mobile App Developer%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-mobile-app-developer-jobs.html')
        ->and($job->job_type)->toBe('Remote')
        // The occupation runs from under $82,460 to over $214,670, so any
        // single range on the listing would be an invention.
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader');
});

it('does not duplicate the web developer guide that already covers that occupation', function () {
    // The two posts sit either side of a $43,330 median gap and must point at
    // each other rather than compete for the same query.
    $mobile = Blog::where('slug', MOBILE_BLOG_SLUG)->value('content');

    expect($mobile)->toContain('/blog/web-developer-jobs-in-usa');
    expect(Blog::where('slug', MOBILE_BLOG_SLUG)->value('title'))->not->toBe('Web Developer Jobs in USA');
});

it('is linked back from every sibling development guide', function () {
    // The set is only useful to a reader, or to a crawler, if it is complete
    // in both directions — a new guide nothing points at is an orphan.
    $this->seed(Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\WebDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\ReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\JavaDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\FullStackDeveloperJobsUsaBlogSeeder::class);

    $siblings = [
        'software-developer-jobs-in-usa',
        'web-developer-jobs-in-usa',
        'react-developer-jobs-in-usa',
        'java-developer-jobs-in-usa',
        'front-end-developer-jobs-in-usa',
        'full-stack-developer-jobs-in-usa',
    ];

    foreach ($siblings as $slug) {
        // toContain takes varargs, so the slug goes in the variable name
        // rather than a second argument that would become another needle.
        expect(Blog::where('slug', $slug)->value('content'))
            ->toContain('/blog/'.MOBILE_BLOG_SLUG);
    }
});
