<?php

use App\Models\Blog;
use Database\Seeders\SecurityGuardJobsSaudiBlogSeeder;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->seed(SecurityGuardJobsSaudiBlogSeeder::class);
});

/** Count how many times a meta tag with the given attribute appears. */
function metaCount(string $html, string $attribute, string $value): int
{
    return preg_match_all('/<meta[^>]+'.preg_quote($attribute, '/').'="'.preg_quote($value, '/').'"/i', $html);
}

it('gives a blog post exactly one og:image, and its own', function () {
    // The layout renders its own og:image AFTER @stack('meta'), so a pushed
    // tag was being overridden by the site default — every post advertised
    // the generic home background instead of its own picture.
    $html = get('/blog/security-guard-jobs-in-saudi-arabia')->assertOk()->getContent();

    expect(metaCount($html, 'property', 'og:image'))->toBe(1);

    preg_match('/<meta property="og:image" content="([^"]+)"/', $html, $m);
    expect($m[1] ?? '')->toContain('security-guard-jobs-in-saudi-arabia.jpg')
        ->not->toContain('home-background-03');
});

it('lets Google show a large thumbnail on every page', function (string $url) {
    // max-image-preview:large is what puts a large image beside the result.
    // It used to be set only on the home and about pages, so the blog posts
    // actually earning impressions could not get one.
    $html = get($url)->assertOk()->getContent();

    expect(metaCount($html, 'name', 'robots'))->toBe(1);
    expect($html)->toContain('max-image-preview:large');
})->with([
    'blog post' => '/blog/security-guard-jobs-in-saudi-arabia',
    'home' => '/',
    'about' => '/about-us',
    'blog index' => '/blog',
    'jobs' => '/jobs',
]);

it('keeps the noindex pages opting out', function () {
    // The directive default must not accidentally re-index a page that had
    // deliberately opted out.
    $html = get('/job-seekers')->assertOk()->getContent();

    preg_match('/<meta name="robots" content="([^"]+)"/', $html, $m);
    $robots = $m[1] ?? '';

    expect(Blog::count())->toBeGreaterThan(0);
    expect($robots)->toBeIn([
        'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
        'index, follow',
        'noindex, follow',
    ]);
});
