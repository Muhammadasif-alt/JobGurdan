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
