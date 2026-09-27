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

    expect($labels)->toContain('Jobs', 'About', 'Partners', 'Career Advice')
        ->and($labels)->not->toContain('Employers')
        ->and($labels)->not->toContain('Job Seekers');
});

it('gives the header slots to About and Partners, not to the two directories', function () {
    // Companies and Talent are directories people reach from a job, not
    // destinations in their own right; About and Partners are the pages that
    // answer "who are these people" before anyone applies.
    $labels = navLabels();

    expect(array_slice($labels, 0, 6))->toBe(['Home', 'Jobs', 'About', 'Partners', 'Resume Writing', 'Career Advice'])
        ->and($labels)->not->toContain('Companies')
        ->and($labels)->not->toContain('Talent');
});

it('keeps Companies and Talent reachable from the footer of every page', function (string $url) {
    // They left the header, so the footer is the only thing keeping them
    // crawlable — and the only way a visitor still finds them.
    $html = get($url)->assertOk()->getContent();

    expect($html)->toContain('href="'.route('jobs.companies').'"><i class="icon-feather-chevron-right"></i> <span>Companies</span>')
        ->toContain('href="'.route('job-seekers.index').'"><i class="icon-feather-chevron-right"></i> <span>Talent</span>');
})->with([
    'home' => '/',
    'jobs' => '/jobs',
    'about' => '/about-us',
]);

it('marks the page you are on as current', function (string $path, string $label) {
    $nav = navBlock($path);

    // The current link carries the class; find the anchor it belongs to.
    preg_match('#<a[^>]*class="[^"]*current[^"]*"[^>]*>\s*([^<]+?)\s*</a>#s', $nav, $m);

    expect($m[1] ?? null)->toBe($label);
})->with([
    'home' => ['/', 'Home'],
    'jobs' => ['/jobs', 'Jobs'],
    'about' => ['/about-us', 'About'],
    'partners' => ['/partners', 'Partners'],
    'resume' => ['/resume-writing-services', 'Resume Writing'],
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

it('leaves a guest exactly one button in the header', function () {
    // Post a Job and Sign In sat side by side competing for the same glance.
    // Sign In wins because the login page carries the "create one free" link,
    // so it serves the visitor with an account and the one without.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('Sign In')
        ->not->toContain('Post a Job')
        ->not->toContain('post-job-btn')
        ->not->toContain('Register CV')
        ->not->toContain('register-cv-btn');

    expect(substr_count($html, 'class="log-in-button log-in-primary"'))->toBe(1);
});

it('sends the visitor without an account on to registration from the login page', function () {
    // The single header button only works as an entry point for both cases
    // because the page it lands on offers the other one.
    get(route('login'))->assertOk()->assertSee('href="'.route('register').'"', false);
});

it('gives that one button the filled treatment', function () {
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '#header .utf-right-side .log-in-primary {');
    expect($start)->not->toBeFalse('log-in-primary styles not found');
    expect(substr($html, $start, 320))->toContain('linear-gradient(135deg, #1b3a6b, #2f7fc9)');
});

it('keeps the header inside the window on a small laptop', function () {
    // Bootstrap holds .container at 960px between 992 and 1199, but the
    // desktop header starts at 1100. For those 100px a navbar measuring
    // roughly 1245px was being squeezed into 930px: the Post a Job label
    // broke at its spaces into three lines and Sign In fell off the edge.
    $html = str_replace('
', '
', get('/')->assertOk()->getContent());

    // The wide container now starts where the desktop header does.
    expect($html)->toContain('@media (min-width: 1100px) {
            .container { max-width: 1800px !important; }')
        ->not->toContain('@media (min-width: 1200px) {
            .container { max-width: 1800px !important; }');

    // Same header, tightened, for every laptop narrower than 1400px: the
    // wordmark is capped by height rather than a fixed width (a fixed one
    // overflowed once the logo changed), and the nav gives up its padding.
    $start = strpos($html, '@media (min-width: 992px) and (max-width: 1399px) {');
    expect($start)->not->toBeFalse('small-laptop header rules not found');

    expect(substr($html, $start, 1200))
        ->toContain('height: 40px !important;')
        ->toContain('max-width: 100% !important;')
        ->toContain('padding: 9px 10px !important;');
});

it('never lets a header button break its label across lines', function () {
    // The label is what collapsed: with no nowrap the button could shrink to
    // its longest word and stack "Post / a / Job".
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '#header .utf-right-side .utf-header-widget-item {');
    expect($start)->not->toBeFalse('widget item styles not found');
    expect(substr($html, $start, 1500))
        ->toContain('flex-shrink: 0 !important;')
        ->toContain('white-space: nowrap !important;');
});
