<?php

use App\Models\Blog;
use App\Models\BlogCatgories;
use App\Models\Scholarship;

function makeSitemapPost(array $overrides = []): Blog
{
    $category = BlogCatgories::firstOrCreate(['slug' => 'career-advice'], ['name' => 'Career Advice', 'description' => 'Guides']);

    return Blog::create(array_merge([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => 'Sitemap Sample Guide',
        'slug' => 'sitemap-sample-guide-'.uniqid(),
        'excerpt' => 'A short excerpt.',
        'content' => '<p>Body text.</p>',
        'status' => 'published',
        'published_at' => now(),
    ], $overrides));
}

it('lists published guides and scholarships on the HTML sitemap and hides drafts', function () {
    $post = makeSitemapPost(['title' => 'Visible Sitemap Guide']);
    makeSitemapPost(['title' => 'Hidden Draft Guide', 'status' => 'draft']);
    $scholarship = Scholarship::factory()->create(['title' => 'Visible Sitemap Award']);
    Scholarship::factory()->draft()->create(['title' => 'Hidden Draft Award']);

    $this->get('/sitemap')
        ->assertOk()
        ->assertSee('Visible Sitemap Guide')
        ->assertSee(route('blog.show', $post->slug), false)
        ->assertSee('Visible Sitemap Award')
        ->assertSee(route('scholarships.show', $scholarship->slug), false)
        ->assertSee(route('pages.disclaimer'), false)
        ->assertDontSee('Hidden Draft Guide')
        ->assertDontSee('Hidden Draft Award');
});

it('links the HTML sitemap from the footer and the XML sitemap', function () {
    $this->get(route('pages.disclaimer'))->assertOk()->assertSee(route('pages.sitemap'), false);
});

it('opens links to other sites in a new tab and leaves JobGader links alone', function () {
    $siteUrl = rtrim(config('app.url'), '/');
    $post = makeSitemapPost(['content' => '<p><a href="https://www.gov.uk/x" rel="noopener">GOV</a> '
        .'<a href="https://example.org/y">Plain</a> '
        .'<a href="https://example.net/z" target="_self" rel="nofollow">Kept</a> '
        .'<a href="/blog/other">Inside</a> '
        .'<a href="'.$siteUrl.'/jobs">Absolute inside</a></p>']);

    $html = $post->display_content;

    expect($html)->toContain('<a href="https://www.gov.uk/x" rel="noopener" target="_blank">')
        ->toContain('<a href="https://example.org/y" target="_blank" rel="noopener">')
        ->toContain('<a href="https://example.net/z" target="_self" rel="nofollow">')
        ->toContain('<a href="/blog/other">')
        ->toContain('<a href="'.$siteUrl.'/jobs">');

    $this->get(route('blog.show', $post->slug))->assertOk()->assertSee('href="https://example.org/y" target="_blank" rel="noopener"', false);
});

it('links the official Facebook page from the footer and the Organization schema', function () {
    $facebook = config('site.social.facebook');

    expect($facebook)->toStartWith('https://www.facebook.com/');

    $this->get('/')
        ->assertOk()
        ->assertSee('href="'.$facebook.'" target="_blank" rel="noopener"', false)
        ->assertSee('"sameAs": '.json_encode([$facebook], JSON_UNESCAPED_SLASHES), false);
});

it('hides the social line when no profile is set', function () {
    config(['site.social' => ['facebook' => '', 'linkedin' => '']]);

    $this->get(route('pages.disclaimer'))->assertOk()->assertDontSee('Follow JobGader');
});
