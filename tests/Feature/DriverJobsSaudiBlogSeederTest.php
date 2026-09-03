<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\DriverJobsSaudiBlogSeeder;

const SAUDI_APPLY_URL = 'https://www.indeed.com/q-driving-jobs-in-saudi-jobs.html?vjk=4413726e409c44ba';
const SAUDI_BLOG_SLUG = 'driver-jobs-in-saudi-arabia-for-foreigners';
const SAUDI_JOB_SLUG = 'driver-private-delivery-heavy-truck-saudi-arabia';

beforeEach(function () {
    $this->seed(DriverJobsSaudiBlogSeeder::class);
});

it('publishes the Saudi driver guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', SAUDI_BLOG_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/driver-jobs-in-saudi-arabia-for-foreigners.jpg')
        ->and($blog->content)->toContain('driver-jobs-in-saudi-arabia-riyadh.jpg')
        ->and($blog->meta_title)->not->toBeEmpty()
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/'.SAUDI_BLOG_SLUG)
        ->assertOk()
        ->assertSee('Driver Jobs in Saudi Arabia for Foreigners')
        ->assertSee('Company Driver or House Driver')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/truck-driver-jobs-in-usa-with-visa-sponsorship', false)
        ->assertSee(SAUDI_APPLY_URL, false);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', SAUDI_BLOG_SLUG)->value('content'));

    // The "People Also Search For" block uses the same h3/p shape, so the
    // extractor must stop at the end of the FAQ section rather than sweep it up.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Driver salary in Saudi Arabia per month');

    $this->get('/blog/'.SAUDI_BLOG_SLUG)->assertSee('"FAQPage"', false);
});

it('creates a listing that separates the Labour Law and domestic worker routes', function () {
    $job = Job::where('position', 'like', 'Driver — %')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(SAUDI_APPLY_URL)
        ->and($job->salary_currency)->toBe('SAR')
        ->and($job->salary_period)->toBe('Monthly')
        ->and($job->location->name)->toBe('Saudi Arabia')
        ->and($job->description)->toContain('Musaned')
        ->and($job->description)->toContain('Never pay for a visa');
});

it('drops JobPosting markup for an Indeed search link carrying a vjk parameter', function () {
    $response = $this->get('/jobs/'.SAUDI_JOB_SLUG);

    $response->assertOk()->assertSee(SAUDI_APPLY_URL, false);

    // "vjk=" is Indeed's search-page preview parameter, not the "jk=" permalink
    // marker, so this page must not claim to describe a single vacancy.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(DriverJobsSaudiBlogSeeder::class);

    expect(Blog::where('slug', SAUDI_BLOG_SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Driver — %')->count())->toBe(1);
});
