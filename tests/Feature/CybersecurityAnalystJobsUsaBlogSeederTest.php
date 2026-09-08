<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\CybersecurityAnalystJobsUsaBlogSeeder;

const CYBER_BLOG_SLUG = 'cybersecurity-analyst-jobs-in-usa';

beforeEach(function () {
    $this->seed(CybersecurityAnalystJobsUsaBlogSeeder::class);
});

it('publishes the cybersecurity guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', CYBER_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/cybersecurity-analyst-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('cybersecurity-analyst-jobs-in-usa-soc.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        // blogs.excerpt is a VARCHAR(255) and SQLite does not enforce it.
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('renders with its long-tail sections and the search link', function () {
    $this->get('/blog/'.CYBER_BLOG_SLUG)
        ->assertOk()
        ->assertSee('Cybersecurity Analyst Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('https://www.indeed.com/q-cybersecurity-analyst-jobs.html', false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', CYBER_BLOG_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('SOC analyst jobs USA');

    $this->get('/blog/'.CYBER_BLOG_SLUG)->assertSee('"FAQPage"', false);
});

it('anchors the pay on the information security analyst occupation', function () {
    $content = Blog::where('slug', CYBER_BLOG_SLUG)->value('content');

    expect($content)->toContain('$129,180')
        ->toContain('$75,090')
        ->toContain('$199,850')
        // 21% growth is real, but on 14,100 openings against 106,100 for
        // software developers — quoting only the rate overstates the door.
        ->toContain('21 per cent')
        ->toContain('14,100')
        ->toContain('106,100');
});

it('corrects the draft on CISSP and CISM being entry certifications', function () {
    // The draft listed both beside Security+ as entry qualifications and said
    // certifications substitute for experience. Neither can be held without
    // five years, so the advice is backwards rather than merely optimistic.
    $content = Blog::where('slug', CYBER_BLOG_SLUG)->value('content');

    expect($content)->toContain('Associate of ISC2')
        ->toContain('six years to earn the five')
        ->toContain('three must be in security management')
        ->toContain('management years can never be waived')
        // The waiver list changed under everyone's feet in April 2026.
        ->toContain('1 April 2026');
});

it('warns that cleared postings need citizenship and a sponsor', function () {
    // The draft named Fort Meade, Northern Virginia and DoD policy without
    // once saying those roles need a clearance — which for much of this
    // site's audience rules them out before they start.
    $content = Blog::where('slug', CYBER_BLOG_SLUG)->value('content');

    expect($content)->toContain('A clearance requires US citizenship')
        ->toContain('You cannot obtain one yourself');
});

it('creates an aggregated listing that quotes no salary it cannot support', function () {
    $job = Job::where('position', 'like', 'Cybersecurity Analyst%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://www.indeed.com/q-cybersecurity-analyst-jobs.html')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->description)->toContain('not by JobGader')
        // The clearance question belongs on the listing too, not just the post.
        ->and($job->description)->toContain('security clearance');
});

it('is linked back from the neighbouring technical guides', function () {
    $this->seed(Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\PythonDeveloperJobsUsaBlogSeeder::class);
    $this->seed(Database\Seeders\MobileAppDeveloperJobsUsaBlogSeeder::class);

    foreach (['software-developer-jobs-in-usa', 'python-developer-jobs-in-usa', 'mobile-app-developer-jobs-in-usa'] as $slug) {
        expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.CYBER_BLOG_SLUG);
    }
});
