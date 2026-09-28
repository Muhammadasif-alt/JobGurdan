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

    expect(array_slice($labels, 0, 6))->toBe(['Home', 'Jobs', 'Companies', 'Talent', 'Resume Writing', 'Career Advice']);
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

it('gives employers the one filled call to action in the header', function () {
    // Register CV and Post a Job sat side by side competing for the same
    // glance, and Sign In already covers the seeker who wants an account.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('post-job-btn')
        ->toContain('Post a Job')
        ->toContain('Sign In')
        ->not->toContain('Register CV')
        ->not->toContain('register-cv-btn');

    // It inherits the treatment Register CV used to carry rather than the
    // outline it had while it was the secondary button.
    $start = strpos($html, '.utf-header-widget-item .post-job-btn {');
    expect($start)->not->toBeFalse('post-job-btn styles not found');
    expect(substr($html, $start, 420))->toContain('linear-gradient(135deg, #1b3a6b, #2f7fc9)');
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

    // Same header, tightened, for every laptop narrower than 1400px: the mark
    // is held to 32px tall and lets its width follow, which is what the old
    // fixed 158px was reaching for before the logo changed shape.
    $start = strpos($html, '@media (min-width: 992px) and (max-width: 1399px) {');
    expect($start)->not->toBeFalse('small-laptop header rules not found');

    expect(substr($html, $start, 1200))
        ->toContain('height: 32px !important;')
        ->toContain('width: auto !important;')
        ->toContain('padding: 9px 10px !important;');
});

it('never lets a header button break its label across lines', function () {
    // The label is what collapsed: with no nowrap the button could shrink to
    // its longest word and stack "Post / a / Job".
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '.utf-header-widget-item .post-job-btn {');
    expect(substr($html, $start, 250))->toContain('flex-shrink: 0; white-space: nowrap;');

    $start = strpos($html, '#header .utf-right-side .utf-header-widget-item {');
    expect($start)->not->toBeFalse('widget item styles not found');
    expect(substr($html, $start, 1500))
        ->toContain('flex-shrink: 0 !important;')
        ->toContain('white-space: nowrap !important;');
});

it('draws the supplied JobGader mark, one file per theme', function () {
    // The pair it replaces were hand-built SVGs whose wordmark was a <text>
    // node set in Arial Black, so the logo rendered in whatever the visitor's
    // machine substituted for a font it almost certainly did not have.
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('user/images/jobgader-navbar.png')
        ->toContain('user/images/jobgader-navbar-dark.png')
        ->and($html)->not->toContain('jobgader-navbar.svg')
        ->and($html)->not->toContain('jobgader-dark-logo.svg');

    // The footer sits on the brand gradient, so it takes the dark mark too.
    expect(substr_count($html, 'user/images/jobgader-navbar-dark.png'))->toBe(2);
});

it('ships both marks in the tree the pages ask for and the one the server serves', function () {
    // asset() stamps a /public/ prefix that the old box's document root eats,
    // so the file is read from public/public/... while the cache-busting
    // filemtime is read from public/... Miss either and the logo 404s.
    foreach (['jobgader-navbar.png', 'jobgader-navbar-dark.png'] as $file) {
        expect(file_exists(public_path('user/images/'.$file)))->toBeTrue($file.' missing for the stamp')
            ->and(file_exists(public_path('public/user/images/'.$file)))->toBeTrue($file.' missing for the server');
    }
});

it('lets the mark keep its own proportions in the header', function () {
    // Every breakpoint used to pin a width as well as a height, which squeezed
    // a 4.16:1 mark into a 5:1 box and left it floating in dead space.
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '#header #logo img.logo-light,');
    expect($start)->not->toBeFalse('logo sizing rules not found');

    expect(substr($html, $start, 320))->toContain('width: auto !important;')
        ->toContain('height: 40px !important;');
});
