<?php

use function Pest\Laravel\get;

/** The markup between <nav id="navigation"> and its closing tag. */
function navBlock(string $path = '/'): string
{
    $html = get($path)->assertOk()->getContent();

    $start = strpos($html, '<nav id="navigation">');
    expect($start)->not->toBeFalse('primary navigation not found');

    return substr($html, $start, strpos($html, '</nav>', $start) - $start);
}

/**
 * The visible label of every top-level nav link, in the order they render.
 *
 * @return list<string>
 */
function navLabels(string $path = '/'): array
{
    preg_match_all('#<a[^>]*>\s*([^<]+?)\s*</a>#s', navBlock($path), $m);

    return array_values(array_filter(array_map('trim', $m[1]), fn (string $l): bool => $l !== ''));
}

it('names each nav link after what is on the page, not who it is for', function () {
    // "Employers" led to a list of companies and "Job Seekers" to a directory
    // of other candidates, so both sent the audience they named somewhere else.
    $labels = navLabels();

    expect($labels)->toContain('Jobs', 'Companies', 'Talent', 'Career Advice')
        ->and($labels)->not->toContain('Employers')
        ->and($labels)->not->toContain('Job Seekers');
});

it('puts Jobs first after Home, ahead of the two directories', function () {
    $labels = navLabels();

    expect(array_slice($labels, 0, 5))->toBe(['Home', 'Jobs', 'Companies', 'Talent', 'Career Advice']);
});

it('marks the page you are on as current', function (string $path, string $label) {
    $nav = navBlock($path);

    // The current link carries the class; find the anchor it belongs to.
    preg_match('#<a[^>]*class="[^"]*current[^"]*"[^>]*>\s*([^<]+?)\s*</a>#s', $nav, $m);

    expect($m[1] ?? null)->toBe($label);
})->with([
    'home' => ['/', 'Home'],
    'jobs' => ['/jobs', 'Jobs'],
    'companies' => ['/companies', 'Companies'],
    'talent' => ['/job-seekers', 'Talent'],
    'advice' => ['/blog', 'Career Advice'],
]);

it('styles the current page as a filled button rather than the hover grey', function () {
    // Hover and current were both flat #f5f5f7, so there was no way to tell
    // which page you were on.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('#header #navigation > ul > li > a.current,')
        ->toContain('background: #1b3a6b !important;')
        // Dark mode had no override at all, so the light grey was used there too.
        ->toContain('html.dark-mode #header #navigation > ul > li > a.current,');
});

it('gives employers a way in from the header', function () {
    // "Employers" was the only employer-facing link and it went to a company
    // list; the header carried a Register CV button for seekers and nothing
    // for the other side.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('post-job-btn')
        ->toContain('Post a Job')
        ->toContain('Register CV');
});
