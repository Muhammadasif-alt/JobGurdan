<?php

use App\Models\Blog;
use App\Models\BlogCatgories;

function makeGuide(BlogCatgories $category, string $slug, string $title, string $tags, string $publishedAt = '2026-10-01'): Blog
{
    return Blog::create([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => $title,
        'slug' => $slug,
        'excerpt' => 'Excerpt for '.$title,
        'content' => '<p>Body of '.$title.'</p>',
        'tags' => $tags,
        'featured_image' => 'blogs/x.jpg',
        'meta_title' => $title,
        'meta_description' => 'Description for '.$title,
        'reading_time' => 3,
        'status' => 'published',
        'published_at' => $publishedAt,
    ]);
}

beforeEach(function () {
    $this->category = BlogCatgories::create(['name' => 'Visa Sponsorship', 'slug' => 'visa-sponsorship', 'description' => 'x']);
});

it('ranks guides on the same occupation and country above merely newer ones', function () {
    $post = makeGuide($this->category, 'hgv-visa-uk', 'HGV Driver Jobs in the UK With Visa Sponsorship', 'hgv driver jobs uk, hgv visa sponsorship, uk lorry driver visa', '2026-10-01');
    $match = makeGuide($this->category, 'hgv-pay-uk', 'HGV Driver Salary in the UK', 'hgv driver salary uk, uk lorry driver pay', '2026-09-01');
    $other = makeGuide($this->category, 'hotel-usa', 'Hotel Housekeeper Jobs in the USA', 'hotel housekeeper jobs usa, h-2b hotel', '2026-10-05');

    $related = $post->relatedPosts(5);

    expect($related->first()->id)->toBe($match->id)
        ->and($related->pluck('id')->all())->toContain($other->id)
        ->and($related->pluck('id')->all())->not->toContain($post->id);
});

it('prefers the same country over the same occupation in another country', function () {
    $post = makeGuide($this->category, 'truck-usa', 'Truck Driver Salary in the USA', 'truck driver salary usa, cdl driver salary');
    $sameCountry = makeGuide($this->category, 'warehouse-usa', 'Warehouse Worker Salary in the USA', 'warehouse worker salary usa, warehouse pay usa', '2026-08-01');
    $otherCountry = makeGuide($this->category, 'truck-uk', 'Truck Driver Pay in the UK', 'truck driver pay uk, lorry driver uk', '2026-08-01');

    $ids = $post->relatedPosts(2)->pluck('id')->all();

    expect($ids)->toContain($sameCountry->id)->toContain($otherCountry->id);
});

it('fills the list from the same category when nothing shares a topic', function () {
    $post = makeGuide($this->category, 'a', 'Alpha Guide', 'alpha');
    $filler = makeGuide($this->category, 'b', 'Zeta Guide', 'zeta');

    expect($post->relatedPosts(5)->pluck('id')->all())->toBe([$filler->id]);
});

it('shows related guides on the blog page', function () {
    $post = makeGuide($this->category, 'hgv-visa-uk', 'HGV Driver Jobs in the UK', 'hgv driver jobs uk');
    makeGuide($this->category, 'hgv-pay-uk', 'HGV Driver Salary in the UK', 'hgv driver salary uk');

    $this->get('/blog/'.$post->slug)->assertOk()->assertSee('HGV Driver Salary in the UK');
});

it('filters the blog by tag and keeps tag pages out of the index', function () {
    makeGuide($this->category, 'hgv-visa-uk', 'HGV Driver Jobs in the UK', 'hgv driver jobs uk, uk visa');
    makeGuide($this->category, 'hotel-usa', 'Hotel Housekeeper Jobs in the USA', 'hotel housekeeper jobs usa');

    $response = $this->get('/blog?tag=hgv+driver+jobs+uk');

    $response->assertOk()
        ->assertSee('HGV Driver Jobs in the UK')
        ->assertDontSee('Hotel Housekeeper Jobs in the USA')
        ->assertSee('noindex, follow', false);
});

it('leaves the plain blog archive indexable', function () {
    makeGuide($this->category, 'hgv-visa-uk', 'HGV Driver Jobs in the UK', 'hgv driver jobs uk');

    $this->get('/blog')->assertOk()->assertDontSee('noindex, follow', false);
});
