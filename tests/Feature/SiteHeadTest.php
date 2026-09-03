<?php

use function Pest\Laravel\get;

it('keeps the Search Console verification tag in the site head', function () {
    get('/')->assertOk()
        ->assertSee('name="google-site-verification" content="NEZhtXbrZZkQYcz5kQO1hT17Vs27bb3VYUgrjUTUeQ0"', false);
});

it('keeps blog and listing images crawlable in robots.txt', function () {
    $robots = file_get_contents(public_path('robots.txt'));

    // Blog images are served from /public/storage/; disallowing that path
    // hides every post image from Image Search and from rich results.
    expect($robots)->toContain('Allow: /public/storage/')
        ->not->toContain('Disallow: /public/storage/')
        ->not->toContain('Disallow: /storage/')
        ->not->toContain('Crawl-delay')
        ->and($robots)->toContain('Sitemap: https://jobgader.com/sitemap.xml');
});
