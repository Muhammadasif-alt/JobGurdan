<?php

it('keeps the footer headings readable in both themes', function () {
    $css = (string) file_get_contents(
        resource_path('views/user/layouts/master.blade.php')
    );

    // Dark mode painted the footer headings #1b3a6b on a #0f1115 background,
    // about 1.7:1 — the section titles were effectively invisible. Both themes
    // now use the same brand blue the section headings use.
    $flat = preg_replace('/\s+/', ' ', $css);

    expect($flat)
        ->toContain('#footer .utf-footer-item-links h3 { color: #3182ce !important;')
        ->toContain('html.dark-mode #footer .utf-footer-item-links h3 { color: #3182ce !important; }')
        ->not->toContain('#footer .utf-footer-item-links h3 { color: #4d9eff !important;')
        ->not->toContain('html.dark-mode #footer .utf-footer-item-links h3 { color: #1b3a6b !important; }')
        ->not->toContain('html.dark-mode #footer .utf-footer-item-links ul li a:hover { color: #1b3a6b !important; }');
});

it('ships the images the homepage now points at', function () {
    $blade = (string) file_get_contents(resource_path('views/user/index.blade.php'));

    preg_match_all("#asset\('(public/user/images/[\w.-]+)'\)#", $blade, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_unique($matches[1]) as $path) {
        expect(file_exists(public_path($path)))->toBeTrue("missing image: {$path}");
    }
});
