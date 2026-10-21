<?php

it('keeps phones from waiting on animations and render-blocking fonts', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->toContain("document.documentElement.classList.add('aos-off')")
        ->toContain('html.aos-off [data-aos]')
        ->toContain('rel="stylesheet" media="print" onload="this.media=\'all\'"')
        ->toContain('<link rel="preload" as="image" href="')
        ->toContain('home.webp" type="image/webp" fetchpriority="high"')
        ->toContain('return window.innerWidth < 768;');
});

it('swaps in fallback text while the icon fonts load', function () {
    foreach (['public/user/css/icons.css', 'public/public/user/css/icons.css'] as $path) {
        $css = file_get_contents(base_path($path));

        expect(substr_count($css, '@font-face'))->toBe(substr_count($css, 'font-display: swap'));
    }
});
