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

it('keeps clear of the back-to-top button', function () {
    config(['site.whatsapp' => '923463035426']);

    // #backtotop is fixed 25px in from the bottom right and only appears once
    // you scroll, so sharing that corner would leave this button hovering
    // over empty space half the time.
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '.wa-float {');
    expect($start)->not->toBeFalse('floating button styles missing');

    $css = substr($html, $start, 400);

    expect($css)->toContain('position: fixed; left: 25px; bottom: 25px;')
        ->not->toContain('right:');
});

it('drops the sliding label where there is no hover', function () {
    config(['site.whatsapp' => '923463035426']);

    // On a touch screen the label would never open, so it goes rather than
    // sitting there permanently collapsed.
    expect(get('/')->getContent())->toContain('.wa-float span { display: none; }');
});
