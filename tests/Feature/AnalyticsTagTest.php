<?php

use function Pest\Laravel\get;

it('emits no tracking script when no analytics id is configured', function () {
    config(['services.google.analytics_id' => null, 'services.google.tag_manager_id' => null]);

    $html = get('/')->assertOk()->getContent();

    expect($html)->not->toContain('googletagmanager.com/gtag/js')
        ->not->toContain('googletagmanager.com/gtm.js')
        // The old site's IDs were hardcoded here, sending this site's traffic
        // to a property belonging to a different domain.
        ->not->toContain('G-2NKX5SJMB7')
        ->not->toContain('GTM-WTHF244L');
});

it('emits the configured GA4 id and nothing else', function () {
    config(['services.google.analytics_id' => 'G-TEST12345', 'services.google.tag_manager_id' => null]);

    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('gtag/js?id=G-TEST12345')
        ->toContain('gtag(\'config\', "G-TEST12345")')
        ->not->toContain('googletagmanager.com/gtm.js');
});

it('emits the Tag Manager container and its noscript fallback when set', function () {
    config(['services.google.analytics_id' => null, 'services.google.tag_manager_id' => 'GTM-TEST123']);

    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('"GTM-TEST123"')
        ->toContain('googletagmanager.com/ns.html?id=GTM-TEST123');
});
