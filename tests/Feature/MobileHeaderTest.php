<?php

use function Pest\Laravel\get;

/**
 * The mobile bar is laid out by CSS the layout ships inline, so the contract is
 * asserted against the rendered head rather than against a browser.
 */
function mobileHeaderCss(): string
{
    return get('/')->assertOk()->getContent();
}

it('promotes the logo and the menu to grid items of the header row', function () {
    // grid-column only applies to a grid's direct children. Both sit inside
    // .utf-left-side, so without this the panel opened as a narrow stack beside
    // the logo instead of spanning the row underneath it.
    expect(mobileHeaderCss())
        ->toContain('#header .utf-left-side { display: contents !important; }')
        ->toContain('grid-template-columns: 1fr auto 1fr;');
});

it('opens the menu as a full-width row under the bar', function () {
    $html = mobileHeaderCss();

    expect($html)->toContain('grid-column: 1 / -1;')
        ->toContain('#header.nav-open #navigation { display: block !important; }');
});

it('lets the bar grow when the menu is open instead of overlapping the hero', function () {
    expect(mobileHeaderCss())->toContain('height: auto !important;');
});

it('leaves no dead strip under the bar while the menu is shut', function () {
    // A row-gap applies whether or not the second row has anything in it, and
    // measured live it made the bar six pixels taller than its only row, which
    // is what read as the logo sitting high.
    expect(mobileHeaderCss())->toContain('row-gap: 0 !important;');
});

it('centres the mark itself rather than the theme margin around it', function () {
    // justify-self centres the margin box, so the theme's right margin on #logo
    // left the wordmark twenty pixels off centre.
    expect(mobileHeaderCss())->toContain('margin: 0 !important;');
});

it('no longer builds the off-canvas drawer that slid the whole site sideways', function () {
    $script = file_get_contents(public_path('user/js/custom_jquery.js'));

    expect($script)->not->toContain('mmenu()')
        ->toContain("toggleClass('nav-open', open)");
});

it('carries the new wordmark in both themes', function () {
    $html = mobileHeaderCss();

    expect($html)->toContain('user/images/sajjad-navbar.png')
        ->toContain('user/images/sajjad-navbar-dark.png')
        ->toContain('alt="Sajjad Digital Services"');
});

it('cache-busts the script that drives the menu', function () {
    // It is served with a seven-day max-age and no query, so a phone that
    // loaded the old off-canvas build kept running it after the fix shipped.
    expect(mobileHeaderCss())->toMatch('#custom_jquery\.js\?v=[0-9a-f]+#');
});

it('keeps the controls narrow enough to stay clear of a centred logo', function () {
    // At 56px the pill plus the hamburger were wider than their column, and
    // justify-self:end pushed the overflow left across the wordmark.
    expect(mobileHeaderCss())->toContain('width: 46px !important;')
        ->toContain('transform: translateX(18px) !important;');
});

it('drops the theme transform that lifted the mark off the middle of the bar', function () {
    // #logo img carries top:50% with a -50% translate, a centring trick for a
    // block of fixed height. Inside a flex box it stacks with the box's own
    // centring and pins the mark to the top of the bar.
    expect(mobileHeaderCss())->toContain('transform: none !important;')
        ->toContain('position: static !important;');
});

it('keeps the wide-desktop bar inside the viewport between 1400 and 1600', function () {
    // Measured live at 1440: the bar needed 1531px and the Sign In button was
    // cut off the right edge, because the new wordmark is wider than the old.
    expect(mobileHeaderCss())
        ->toContain('@media (min-width: 1400px) and (max-width: 1599px)')
        ->toContain('height: 46px !important;');
});

it('lets the grid reserve room for the controls instead of zeroing their column', function () {
    // min-width:0 on .utf-right-side made its column narrower than the controls
    // themselves, and justify-self:end spilled the difference left over the
    // wordmark. A 360px phone showed it while 390 looked fine.
    $css = mobileHeaderCss();

    expect($css)->toContain('@media (max-width: 480px)')
        ->toContain('object-fit: contain !important;');

    preg_match('#\#header \.utf-right-side \{(.+?)\}#s', $css, $m);

    expect($m[1] ?? '')->not->toContain('min-width: 0');
});
