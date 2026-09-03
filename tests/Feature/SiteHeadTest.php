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

it('brands the SEO landing pages as JobGader', function () {
    $titles = collect(glob(resource_path('views/pages/*.blade.php')))
        ->map(fn (string $file): string => (string) file_get_contents($file));

    // "USA Jobs" was the old site's name and survived on 36 landing pages.
    expect($titles->filter(fn (string $body): bool => str_contains($body, 'USA Jobs')))->toBeEmpty();

    $this->get('/remote-jobs-usa')->assertOk()->assertSee('| JobGader</title>', false);
});

it('serves a real favicon at the document root', function () {
    // The docroot is a symlink to public/, so /favicon.ico is this file. It
    // shipped as 0 bytes once, which is what Google's favicon fetcher got.
    $ico = public_path('favicon.ico');

    expect(file_exists($ico))->toBeTrue()
        ->and(filesize($ico))->toBeGreaterThan(100);

    [, $type, $count] = array_values(unpack('v3', file_get_contents($ico, false, null, 0, 6)));

    expect($type)->toBe(1)
        ->and($count)->toBeGreaterThan(0);

    get('/')->assertOk()->assertSee('rel="icon" href="'.url('favicon.ico').'"', false);
});

it('does not lock the unversioned favicon into a year of caching', function () {
    // The PNG icons are ?v= stamped; favicon.ico is not, so a year-long TTL
    // left a stale icon stuck at the CDN and in Google's index.
    foreach (['public/.htaccess', 'public/public/.htaccess'] as $file) {
        expect(file_get_contents(base_path($file)))
            ->not->toContain('ExpiresByType image/x-icon "access plus 1 year"')
            ->toContain('ExpiresByType image/x-icon "access plus 1 day"');
    }
});

it('falls back to a real meta description, not a placeholder', function () {
    $response = get('/blog')->assertOk();

    $response->assertDontSee('content="Find latest jobs"', false);
});
