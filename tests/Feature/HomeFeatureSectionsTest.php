<?php

use App\Services\SiteCoverage;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

beforeEach(function () {
    Cache::flush();
});

/**
 * The rendered homepage, with the two image-and-text rows cut out of it.
 *
 * @return array{html: string, verified: string, cv: string}
 */
function homeSplitRows(): array
{
    $html = get('/')->assertOk()->getContent();

    $row = function (string $heading) use ($html): string {
        preg_match('#<section class="home-split-section[^"]*" aria-labelledby="'.$heading.'">.*?</section>#s', $html, $match);

        return $match[0] ?? '';
    };

    return [
        'html' => $html,
        'verified' => $row('verified-jobs-heading'),
        'cv' => $row('tailored-cv-heading'),
    ];
}

it('shows the verified jobs row and the tailored CV row, even before any job is posted', function () {
    $rows = homeSplitRows();

    expect($rows['verified'])->toContain('id="verified-jobs-heading"')
        ->toContain('Real Hiring')
        ->and($rows['cv'])->toContain('id="tailored-cv-heading"')
        ->toContain('the Job You Want');
});

it('runs the two rows back to back, verified jobs first', function () {
    $rows = homeSplitRows();

    expect($rows['verified'])->not->toBe('')
        ->and($rows['cv'])->not->toBe('');

    $verifiedEnds = strpos($rows['html'], $rows['verified']) + strlen($rows['verified']);
    $cvStarts = strpos($rows['html'], $rows['cv']);

    expect($verifiedEnds)->toBeLessThan($cvStarts)
        ->and(substr($rows['html'], $verifiedEnds, $cvStarts - $verifiedEnds))->not->toContain('<section');
});

it('puts the photo on the left of the first row and on the right of the second', function () {
    $rows = homeSplitRows();

    expect($rows['verified'])->toContain('<div class="home-split">')
        ->and($rows['cv'])->toContain('<div class="home-split home-split-reverse">');

    // The photo comes first in both rows so phones stack it above the text;
    // only the desktop grid moves it to the right in the second row.
    foreach (['verified', 'cv'] as $row) {
        expect(strpos($rows[$row], 'home-split-media'))->toBeLessThan(strpos($rows[$row], 'home-split-body'));
    }

    $css = (string) preg_replace('/\s+/', ' ', $rows['html']);

    expect($css)->toContain('.home-split { display: grid; grid-template-columns: 1fr 1fr;')
        ->toContain('.home-split-reverse .home-split-media { order: 2; }');
});

it('sends each row to the page it promises', function () {
    $rows = homeSplitRows();

    expect($rows['verified'])->toContain('href="'.route('jobs.index').'"')
        ->and($rows['cv'])->toContain('href="'.route('resume-writing').'#resume-enquiry"');

    get(route('resume-writing'))->assertOk()->assertSee('id="resume-enquiry"', false);
});

it('serves each row photo as webp with a jpg fallback at its real size', function (string $row, string $image) {
    $markup = homeSplitRows()[$row];

    expect($markup)->toContain("user/images/{$image}.webp")
        ->toContain("user/images/{$image}.jpg")
        ->toContain('width="1200" height="628"')
        ->toContain('loading="lazy"')
        ->toMatch('/alt="[^"]{30,}"/');

    foreach (['webp', 'jpg'] as $extension) {
        $path = public_path("user/images/{$image}.{$extension}");

        expect(file_exists($path))->toBeTrue("{$image}.{$extension} is missing")
            ->and(array_slice(getimagesize($path), 0, 2))->toBe([1200, 628]);
    }
})->with([
    'verified jobs' => ['verified', 'home-verified-jobs'],
    'tailored cv' => ['cv', 'home-tailored-cv'],
]);

it('keeps the hero eyebrow to claims the listings can back up', function () {
    $html = homeSplitRows()['html'];

    preg_match('#<span class="hero-eyebrow"[^>]*>(.*?)</span>\s*<h1#s', $html, $eyebrow);

    $text = html_entity_decode(trim(strip_tags($eyebrow[1] ?? '')));

    expect($text)->toContain('Real hiring')
        ->toContain(app(SiteCoverage::class)->count().' countries')
        ->not->toMatch('/verified|all over the world|worldwide/i');
});

it('does not promise an employer link or a check most listings do not have', function () {
    // Most live listings point at a job-board search page rather than one
    // employer's advert, and employer accounts publish without a review.
    $verified = homeSplitRows()['verified'];

    expect(strip_tags($verified))->not->toMatch('/original posting|links through to the employer/i')
        ->not->toMatch('/verified|reviewed by our team before|all over the world/i');
});

it('names the countries the board covers instead of claiming every country', function () {
    $verified = homeSplitRows()['verified'];

    expect($verified)->toContain(e(app(SiteCoverage::class)->shortList()))
        ->not->toMatch('/every country/i');
});

it('offers the free CV review without calling the writing itself free', function () {
    // The resume page takes payment for writing once the scope is agreed;
    // only the review costs nothing.
    $cv = homeSplitRows()['cv'];

    expect($cv)->toContain('Free CV Review')
        ->toContain('job advert')
        ->not->toMatch('/free (cv|resume) writing/i');
});

it('themes both rows for dark mode', function (string $selector) {
    expect(file_get_contents(public_path('user/css/site-dark.css')))->toContain('html.dark-mode '.$selector);
})->with([
    'section band' => '.home-split-section',
    'heading' => '.home-split-body h2',
    'body copy' => '.home-split-body p',
    'eyebrow' => '.home-split-eyebrow',
    'points' => '.home-split-points',
]);
