<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\WarehouseUkBlogSeeder;

const WAREHOUSE_APPLY_URL = 'https://www.simplyhired.co.uk/q-uk-warehouse-visa-sponsorship-jobs.html';
const WAREHOUSE_JOB_SLUG = 'warehouse-operative-united-kingdom-united-kingdom';

beforeEach(function () {
    $this->seed(WarehouseUkBlogSeeder::class);
});

it('publishes the warehouse guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', 'warehouse-jobs-uk-visa-sponsorship')->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/warehouse-jobs-uk-visa-sponsorship.jpg')
        ->and($blog->content)->toContain('warehouse-jobs-uk-visa-sponsorship-floor.jpg')
        ->and($blog->meta_title)->not->toBeEmpty()
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('renders the post with its long-tail sections and sibling guides', function () {
    $this->get('/blog/warehouse-jobs-uk-visa-sponsorship')
        ->assertOk()
        ->assertSee('Warehouse Jobs UK Visa Sponsorship')
        ->assertSee('Why Most Warehouse Jobs Don')
        ->assertSee('People Also Search For')
        ->assertSee('/blog/cleaner-jobs-in-london-no-experience-needed', false)
        ->assertSee(WAREHOUSE_APPLY_URL, false);
});

it('creates a listing that is explicit about sponsorship not applying', function () {
    $job = Job::where('position', 'like', 'Warehouse Operative%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe(WAREHOUSE_APPLY_URL)
        ->and($job->salary_currency)->toBe('GBP')
        ->and($job->location->name)->toBe('United Kingdom')
        ->and($job->description)->toContain('already hold the right to work in the UK');
});

it('drops JobPosting markup for a simplyhired.co.uk search link', function () {
    $response = $this->get('/jobs/'.WAREHOUSE_JOB_SLUG);

    $response->assertOk()->assertSee(WAREHOUSE_APPLY_URL, false);

    // The guard matches aggregators without their TLD, so the .co.uk domain
    // counts the same as simplyhired.com would.
    expect($response->getContent())->not->toContain('"JobPosting"');
});

it('does not duplicate the post or job when re-run', function () {
    $this->seed(WarehouseUkBlogSeeder::class);

    expect(Blog::where('slug', 'warehouse-jobs-uk-visa-sponsorship')->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Warehouse Operative%')->count())->toBe(1);
});
