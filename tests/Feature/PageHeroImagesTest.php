<?php

use function Pest\Laravel\get;

/**
 * Pull the per-page banner rules out of the rendered layout.
 *
 * @return array<string, string> selector => image basename
 */
function heroBannerImages(): array
{
    $html = get('/')->assertOk()->getContent();

    preg_match_all(
        '#((?:body \.[a-z0-9-]+,\s*)*body \.[a-z0-9-]+(?:\.[a-z0-9-]+)?)\s*\{\s*background-image:\s*url\([\'"]?[^)]*?user/images/([a-z0-9-]+)\.(?:jpg|png)#i',
        $html,
        $matches,
        PREG_SET_ORDER
    );

    $map = [];

    foreach ($matches as $match) {
        foreach (preg_split('#,\s*#', trim($match[1])) as $selector) {
            $map[trim(str_replace('body ', '', $selector))] = $match[2];
        }
    }

    return $map;
}

it('gives every page banner a photograph rather than one shared image', function () {
    // About, Categories, Locations, Talent, a seeker profile, a blog post and
    // a job detail all opened with hero-diverse-professionals.jpg. The library
    // already held purpose-named images for most of them — jsk-faq,
    // contact-support, home-background-02, industry-security — never wired up.
    $banners = heroBannerImages();

    expect($banners)->toHaveCount(13, 'banner rules not parsed: '.json_encode($banners));

    foreach (['.about-hero', '.cat-hero', '.loc-hero', '.js-hero', '.seeker-hero', '.blog-detail-hero'] as $selector) {
        expect($banners)->toHaveKey($selector);
        expect($banners[$selector])->not->toBe('hero-diverse-professionals', $selector.' still shares the fallback');
    }

    // Only the two catch-alls may share, and they are the last resort for a
    // page that has no banner of its own.
    $shared = array_keys($banners, 'hero-diverse-professionals', true);
    sort($shared);

    expect($shared)->toBe(['.jd-hero', '.utf-page-heading-area']);
});

it('does not point two banners at the same file', function () {
    $banners = heroBannerImages();

    expect($banners)->not->toBeEmpty();

    // The catch-all is allowed to cover the job detail page as well.
    unset($banners['.jd-hero']);

    $repeated = array_keys(array_filter(array_count_values($banners), fn (int $n): bool => $n > 1));

    expect($repeated)->toBe([], 'banner images used twice: '.implode(', ', $repeated));
});

it('ships every banner image it references', function () {
    $banners = heroBannerImages();

    expect($banners)->not->toBeEmpty();

    foreach ($banners as $selector => $name) {
        $found = collect(['jpg', 'png', 'webp'])
            ->first(fn (string $ext): bool => file_exists(public_path("user/images/{$name}.{$ext}")));

        expect($found)->not->toBeNull("{$selector} points at a missing {$name}");
    }
});

it('keeps the home page callout off the about page photograph', function () {
    // callout-1-founders.jpg was a byte-for-byte copy of about-founders.jpg,
    // so the home page, the about page and the resume page all showed one
    // picture of the same two people.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('user/images/callout-1.jpg')
        ->not->toContain('callout-1-founders');
});
