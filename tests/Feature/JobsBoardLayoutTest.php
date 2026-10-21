<?php

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;

beforeEach(function () {
    $lahore = Location::firstOrCreate(['name' => 'Lahore'], ['area' => 'Nationwide', 'country' => 'Pakistan']);
    $it = Category::firstOrCreate(['slug' => 'it-software'], ['name' => 'IT & Software']);
    $sales = Category::firstOrCreate(['slug' => 'sales'], ['name' => 'Sales']);
    $employer = Advertiser::create(['name' => 'Acme Engineering', 'type' => 'Private', 'display_reference' => 'acme-engineering']);
    $portal = Advertiser::create(['name' => 'Pakistan Public Employers (Aggregated)', 'type' => 'Private', 'display_reference' => 'pk-public-aggregated']);

    $make = fn (Advertiser $advertiser, Category $category, string $position, string $type): Job => Job::create([
        'advertiser_id' => $advertiser->id,
        'location_id' => $lahore->id,
        'category_id' => $category->id,
        'position' => $position,
        'description' => '<p>Short description for '.$position.'.</p>',
        'employment_type' => $type,
        'application_url' => 'https://example.com/jobs',
    ]);

    $make($employer, $it, 'Platform Engineer', 'Full-time');
    $make($employer, $sales, 'Weekend Sales Assistant', 'Part-time');
    $make($portal, $it, 'Job Seeker - Public Portal', 'Full-time');

    $category = BlogCatgories::firstOrCreate(['slug' => 'career-advice'], ['name' => 'Career Advice', 'description' => 'Guides']);
    Blog::create([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => 'A Guide Worth Reading',
        'slug' => 'a-guide-worth-reading',
        'excerpt' => 'Short excerpt.',
        'content' => '<p>Body</p>',
        'status' => 'published',
        'published_at' => now(),
    ]);
});

it('renders the three column board with filters, jobs and guides', function () {
    $this->get('/jobs')
        ->assertOk()
        ->assertSee('Find Jobs')
        ->assertSee('Create Alert')
        ->assertSee('Filters')
        ->assertSee('Job Category')
        ->assertSee('Job Type')
        ->assertSee('Career Guides')
        ->assertSee('A Guide Worth Reading')
        ->assertSee('Platform Engineer')
        ->assertSee('Official resource');
});

it('shows counts for each filter group', function () {
    $html = $this->get('/jobs')->assertOk()->getContent();

    expect($html)->toMatch('#<span class="name">Lahore</span>\s*<span class="num">3</span>#')
        ->and($html)->toMatch('#<span class="name">IT &amp; Software</span>\s*<span class="num">2</span>#')
        ->and($html)->toMatch('#<span class="name">Part-time</span>\s*<span class="num">1</span>#');
});

it('narrows the list by job type, category and location', function () {
    $this->get('/jobs?employment=Part')->assertOk()->assertSee('Weekend Sales Assistant')->assertDontSee('Platform Engineer');
    $this->get('/jobs?category=sales')->assertOk()->assertSee('Weekend Sales Assistant')->assertDontSee('Platform Engineer');
    $this->get('/jobs?location=Lahore&category=it-software')->assertOk()->assertSee('Platform Engineer')->assertDontSee('Weekend Sales Assistant');
});

it('keeps the listing type toggle working beside the new filters', function () {
    $this->get('/jobs?type=resource')->assertOk()->assertSee('Job Seeker - Public Portal')->assertDontSee('Platform Engineer');
    $this->get('/jobs?type=vacancy')->assertOk()->assertSee('Platform Engineer')->assertDontSee('Job Seeker - Public Portal');
});

it('shows a clear message and a way back when nothing matches', function () {
    $this->get('/jobs?category=no-such-category')->assertOk()->assertSee('No jobs match your search')->assertSee('Clear all filters');
});
