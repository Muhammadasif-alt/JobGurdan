<?php

use function Pest\Laravel\get;

/**
 * Section headings across the site highlight part of the line with a gradient
 * running #2f7fc9 -> #1b3a6b -> #ffab40. On white that reads fine. On the dark
 * background the #1b3a6b midpoint is close to the page colour, so the middle of
 * the phrase vanished and the orange tail read as muddy brown. The layout now
 * flattens these to the blue the home page already uses.
 */
function flatCss(string $view): string
{
    return (string) preg_replace('/\s+/', ' ', (string) file_get_contents(resource_path($view)));
}

it('flattens gradient heading accents to the home page blue in dark mode', function () {
    $css = flatCss('views/user/layouts/master.blade.php');

    expect($css)
        ->toContain('html.dark-mode h1 .accent, html.dark-mode h2 .accent, html.dark-mode h3 .accent')
        ->toContain('-webkit-text-fill-color: currentColor !important; color: #3182ce !important;');
});

it('leaves no page rule re-applying a dark-starting gradient over that fix', function (string $view) {
    // A rule that is both dark-mode scoped and more specific than the layout's
    // would win, so no view may set a gradient starting or landing on #1b3a6b
    // for a dark-mode heading accent.
    $css = flatCss($view);

    preg_match_all('/html\.dark-mode[^{}]*\.accent[^{}]*\{[^}]*\}/', $css, $rules);

    $offenders = array_values(array_filter(
        $rules[0],
        fn (string $rule): bool => str_contains($rule, 'linear-gradient')
    ));

    expect($offenders)->toBeEmpty("dark-mode accent gradient still set in {$view}: ".implode(' | ', $offenders));
})->with([
    'views/user/about-us.blade.php',
    'views/user/job-seekers/index.blade.php',
    'views/user/companies.blade.php',
    'views/user/companies-jobs.blade.php',
    'views/user/contact-us.blade.php',
    'views/user/blogs.blade.php',
    'views/user/saved-jobs.blade.php',
]);

it('keeps the navy trust band figures off the band colour behind them', function () {
    // The figures used a gradient starting on #1b3a6b, the same colour as the
    // band, so the left of each number was invisible in both themes.
    $css = flatCss('views/user/job-seekers/index.blade.php');

    expect($css)->toContain('.jsk-trust-stat strong {')
        ->and($css)->toContain('color: #8fc4f0;')
        ->and($css)->not->toContain('.jsk-trust-stat strong { display: block; font-size: clamp(28px, 3vw, 40px); font-weight: 800; letter-spacing: -.5px; margin-bottom: 6px; background: linear-gradient(135deg, #1b3a6b, #2f7fc9);');
});

it('counts the covered countries everywhere instead of hardcoding three', function (string $view) {
    // The site covered three countries when these were written and now covers
    // more, so every one of them was stating a number that is no longer true.
    $blade = (string) file_get_contents(resource_path($view));

    expect($blade)->not->toMatch('/<strong>3<\/strong>\s*<span>Countries/i')
        ->and($blade)->not->toMatch('/<div class="stat-num">3<\/div>/');
})->with([
    'views/user/layouts/master.blade.php',
    'views/user/companies-jobs.blade.php',
    'views/user/contact-us.blade.php',
    'views/user/job-seekers/index.blade.php',
    'views/pages/_seo-landing.blade.php',
    'views/user/about-us.blade.php',
]);

it('renders the live country count on the pages that show it', function () {
    $count = app(App\Services\SiteCoverage::class)->count();

    expect(get('/job-seekers')->assertOk()->getContent())
        ->toContain('<strong>'.$count.'</strong><span>Countries Covered</span>');
});
