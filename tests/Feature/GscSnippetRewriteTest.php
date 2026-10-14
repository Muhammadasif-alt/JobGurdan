<?php

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Job;
use App\Models\Location;
use Database\Seeders\GscSnippetRewriteSeeder;

it('keeps every rewritten snippet within the search result limits', function (): void {
    foreach (GscSnippetRewriteSeeder::blogSnippets() as $slug => $snippet) {
        if (isset($snippet['title'])) {
            expect(mb_strlen($snippet['title']))->toBeLessThanOrEqual(60, $slug);
        }

        if (isset($snippet['description'])) {
            expect(mb_strlen($snippet['description']))->toBeLessThanOrEqual(160, $slug);
        }
    }
});

it('rewrites the title and description of an existing guide and leaves its body alone', function (): void {
    $category = BlogCatgories::create(['name' => 'Jobs', 'slug' => 'jobs']);
    $blog = Blog::create([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => 'Welder Jobs in Canada',
        'slug' => 'welder-jobs-in-canada',
        'excerpt' => 'x',
        'content' => '<p>Original body</p>',
        'meta_title' => 'Old title',
        'meta_description' => 'Old description',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->seed(GscSnippetRewriteSeeder::class);

    $blog->refresh();

    expect($blog->meta_title)->toBe('Welder Jobs in Canada 2026: Salary, Red Seal, LMIA')
        ->and($blog->meta_description)->toContain('Red Seal is compulsory')
        ->and($blog->content)->toBe('<p>Original body</p>');
});

it('repairs double-encoded ampersands in a job description', function (): void {
    $location = Location::create(['name' => 'United Kingdom', 'area' => 'UK', 'country' => 'United Kingdom']);
    $advertiser = Advertiser::create(['name' => 'Test Employer', 'type' => 'Private', 'display_reference' => 'test-employer']);
    $category = \App\Models\Category::create(['name' => 'Retail', 'slug' => 'retail']);

    $job = Job::create([
        'category_id' => $category->id,
        'advertiser_id' => $advertiser->id,
        'location_id' => $location->id,
        'position' => 'Shop Assistant',
        'description' => '<p>Role</p>',
        'meta_description' => 'Roles at M&amp;S stores',
        'employment_type' => 'Full-time',
        'job_type' => 'On-site',
    ]);

    $this->seed(GscSnippetRewriteSeeder::class);

    expect($job->fresh()->meta_description)->toBe('Roles at M&S stores');
});

it('renders the employer in the job page title and prints an ampersand once', function (): void {
    $location = Location::create(['name' => 'United Kingdom', 'area' => 'UK', 'country' => 'United Kingdom']);
    $advertiser = Advertiser::create(['name' => 'Marks and Spencer', 'type' => 'Private', 'display_reference' => 'marks-and-spencer-aggregated']);
    $category = \App\Models\Category::create(['name' => 'Retail', 'slug' => 'retail']);

    $job = Job::create([
        'category_id' => $category->id,
        'advertiser_id' => $advertiser->id,
        'location_id' => $location->id,
        'position' => 'Customer Assistant, Marks and Spencer UK Stores',
        'description' => '<p>Role</p>',
        'meta_description' => 'Roles at M&S stores',
        'employment_type' => 'Full-time',
        'job_type' => 'On-site',
    ]);

    $response = $this->get(route('jobs.show', \Illuminate\Support\Str::slug($job->position.'-'.$location->name)));

    $response->assertOk()
        ->assertSee('<title>Customer Assistant at Marks and Spencer | JobGader</title>', false)
        ->assertSee('content="Roles at M&amp;S stores"', false)
        ->assertDontSee('M&amp;amp;S', false);
});
