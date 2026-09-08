<?php

use function Pest\Laravel\get;

/** The footer CSS block from the rendered layout. */
function footerCss(): string
{
    $html = get('/')->assertOk()->getContent();

    $start = strpos($html, '#footer {');
    expect($start)->not->toBeFalse('footer styles not found');

    return substr($html, $start, 8000);
}

it('paints the footer in brand blue over the work photograph', function () {
    $css = footerCss();

    expect($css)->toContain('rgba(47,127,201,.88)')
        ->toContain('footer-recruitment-bg.jpg')
        ->toContain('center / cover no-repeat')
        // The old near-black gradient is gone.
        ->and($css)->not->toContain('#0a1828 0%, #061224 55%');
});

it('ships the footer background image under a name nothing has cached', function () {
    // Static assets are served with max-age=31536000, so overwriting a file
    // in place leaves every returning visitor on the old picture for a year.
    // The rename is what actually ships the change.
    expect(file_exists(public_path('user/images/footer-photo-bg.jpg')))->toBeFalse();

    $path = public_path('user/images/footer-recruitment-bg.jpg');

    expect(file_exists($path))->toBeTrue('footer background image missing');

    // It used to be a crop of subscribe_bg.jpg, the theme's stock collage of
    // sushi, pasta, a barber and a masseur. Nothing on it said "job board".
    expect(md5_file($path))->not->toBe(md5_file(public_path('user/images/subscribe_bg.jpg')));

    // It sits under a near-opaque gradient on every page, so it is served
    // small deliberately; the 624KB original would be waste.
    expect(filesize($path))->toBeLessThan(120 * 1024);

    get('/')->assertOk()->assertSee('user/images/footer-recruitment-bg.jpg', false);
});

it('gives the dark theme the same photograph under a darker overlay', function () {
    $html = get('/')->assertOk()->getContent();

    expect($html)->toContain('html.dark-mode #footer {')
        ->toContain('rgba(18,42,78,.95)')
        // Previously the dark footer was a flat colour with no image at all.
        ->and(substr_count($html, 'footer-recruitment-bg.jpg'))->toBeGreaterThanOrEqual(2);
});

it('drops the text colours that were tuned for a near-black footer', function () {
    // #4d9eff and #3182ce are close enough to the new background to vanish,
    // and #8a9bb0 body text loses its contrast against it.
    $css = footerCss();

    foreach (['#4d9eff', '#3182ce', '#8a9bb0', '#6b7d92'] as $dead) {
        expect($css)->not->toContain('color: '.$dead);
    }
});
