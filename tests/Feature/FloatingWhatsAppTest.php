<?php

use function Pest\Laravel\get;

it('shows a floating WhatsApp button on every page once a number is set', function (string $url) {
    config(['site.whatsapp' => '+92 346 3035426']);

    $html = get($url)->assertOk()->getContent();

    // wa.me only accepts digits, so the configured value is stripped.
    expect($html)->toContain('class="wa-float"')
        ->toContain('wa.me/923463035426')
        ->toContain('aria-label="Message JobGader on WhatsApp"');
})->with([
    'home' => '/',
    'jobs' => '/jobs',
    'resume' => '/resume-writing-services',
]);

it('renders nothing at all when no number is configured', function () {
    config(['site.whatsapp' => null]);

    // A contact button that dials nothing is worse than no button.
    expect(get('/')->getContent())->not->toContain('wa-float')
        ->not->toContain('wa.me');
});

it('stacks above the back-to-top arrow on the same centre line', function () {
    config(['site.whatsapp' => '923463035426']);

    // #backtotop is 42px square and 25px in from the bottom right, so its
    // centre line is 46px from the right edge and its top edge is at 67px.
    // A 54px button at right: 19px shares that centre line, and bottom: 80px
    // leaves 13px of air above it.
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '.wa-float {');
    expect($start)->not->toBeFalse('floating button styles missing');

    expect(substr($html, $start, 700))
        ->toContain('position: fixed; right: 19px; bottom: 80px;')
        ->toContain('width: 54px; height: 54px;')
        // Anchored by its right edge, so the label has to unroll leftwards or
        // the icon slides out from under the cursor as the button grows.
        ->toContain('flex-direction: row-reverse;');
});

it('drops the sliding label where there is no hover', function () {
    config(['site.whatsapp' => '923463035426']);

    // On a touch screen the label would never open, so it goes rather than
    // sitting there permanently collapsed.
    expect(get('/')->getContent())->toContain('.wa-float span { display: none; }');
});
