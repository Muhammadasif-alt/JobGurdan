<?php

it('keeps the footer headings readable in both themes', function () {
    $css = (string) file_get_contents(
        resource_path('views/user/layouts/master.blade.php')
    );

    // Dark mode painted the footer headings #1b3a6b on a #0f1115 background,
    // about 1.7:1 — the section titles were effectively invisible. The footer
    // is dark in both themes, so both now set the headings white.
    $flat = preg_replace('/\s+/', ' ', $css);

    expect($flat)
        ->toContain('#footer .utf-footer-item-links h3 { color: #ffffff !important;')
        ->toContain('html.dark-mode #footer .utf-footer-item-links h3 { color: #ffffff !important; }')
        ->not->toContain('html.dark-mode #footer .utf-footer-item-links h3 { color: #1b3a6b !important; }')
        ->not->toContain('html.dark-mode #footer .utf-footer-item-links ul li a:hover { color: #1b3a6b !important; }');
});

it('ships the images the homepage now points at', function () {
    $blade = (string) file_get_contents(resource_path('views/user/index.blade.php'));

    preg_match_all("#asset\('(public/user/images/[\w.-]+)'\)#", $blade, $matches);

    expect($matches[1])->not->toBeEmpty();

    // The document root is the project root, so asset('public/user/images/x')
    // is served from public/user/images/x. public_path() would prepend a
    // second "public" and land in the stale duplicate tree, where most of
    // these happen to exist and the newest ones do not.
    foreach (array_unique($matches[1]) as $path) {
        $file = public_path(preg_replace('#^public/#', '', $path));

        expect(file_exists($file))->toBeTrue("missing image: {$path}");
    }
});
