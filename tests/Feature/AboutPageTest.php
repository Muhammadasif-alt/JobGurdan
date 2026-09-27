<?php

use function Pest\Laravel\get;

/**
 * The About page carries the company's own account of itself: who founded it,
 * when, and what it actually does. Those are claims, so they are pinned here.
 */
function aboutHtml(): string
{
    return get(route('about.us'))->assertOk()->getContent();
}

it('introduces the founder by name and role', function () {
    $html = aboutHtml();

    expect($html)->toContain('Sajad Rao')
        ->toContain('Founder &amp; Chief Executive')
        ->toContain('class="ab-founder-row"')
        ->toContain('alt="Sajad Rao, founder of Sajad Digital Services"');
});

it('ships the portrait the founder section points at', function (string $file) {
    expect(file_exists(public_path('user/images/'.$file)))->toBeTrue();

    expect(aboutHtml())->toContain('user/images/'.$file);
})->with([
    'jpeg' => 'sajad-owner.jpg',
    'webp' => 'sajad-owner.webp',
]);

it('gives the founding date the same way everywhere it appears', function () {
    // Prose, hero and schema all read 3 October 2022; a page that disagrees
    // with its own structured data is worse than one that omits it.
    $html = aboutHtml();

    expect(substr_count($html, '3 October 2022') + substr_count($html, '3&nbsp;October 2022'))->toBe(3)
        ->and($html)->toContain('"foundingDate": "2022-10-03"');
});

it('names the founder in the organisation schema', function () {
    $html = aboutHtml();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

    $org = collect($blocks[1])
        ->map(fn (string $json): ?array => json_decode(trim($json), true))
        ->first(fn (?array $node): bool => ($node['@type'] ?? null) === 'Organization');

    expect($org)->not->toBeNull()
        ->and($org['founder']['name'])->toBe('Sajad Rao')
        ->and($org['founder']['@type'])->toBe('Person')
        ->and($org['foundingDate'])->toBe('2022-10-03');
});

it('lays Why Choose Us out the way the resume page lays out its points', function () {
    // The owner asked for that treatment: the image beside a single column of
    // rows split by hairlines, not a grid of boxes.
    $html = aboutHtml();

    expect($html)->toContain('.ab-why-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 46px; align-items: stretch; }')
        ->toContain('.ab-why-points { display: grid; grid-template-columns: 1fr; gap: 0; }')
        ->toContain('class="ab-why-eyebrow">Why Choose Us<')
        ->and(substr_count($html, 'class="ab-why-card"'))->toBe(6);
});

it('drops the box grid Why Choose Us replaced', function () {
    // Nine boxes went; leaving their styles behind would be dead weight on
    // every render of the page.
    expect(aboutHtml())->not->toContain('benefit-item')
        ->not->toContain('benefits-row')
        ->not->toContain('benefits-list');
});

it('covers each of the six things the company says it does', function (string $heading) {
    expect(aboutHtml())->toContain('<h3>'.$heading.'</h3>');
})->with([
    'one window' => 'One window for the whole thing',
    'careers' => 'Career guidance and job applications',
    'admissions' => 'University admissions and scholarships',
    'cv' => 'A CV written by a person',
    'promotion' => 'Digital promotion for small businesses',
    'free' => 'Free where it should be free',
]);

it('sends the reader on to the pages those claims rest on', function (string $route) {
    expect(aboutHtml())->toContain('href="'.route($route).'"');
})->with([
    'partners' => 'partners',
    'scholarships' => 'scholarships.index',
    'resume writing' => 'resume-writing',
]);

it('states the mission and the vision the owner set', function () {
    $html = aboutHtml();

    expect($html)->toContain('reliable, affordable digital services within reach')
        ->toContain('digital services desk people in Pakistan trust by default');
});

it('no longer runs a list of US states under a heading about countries', function () {
    // Fifteen US states sat under "Hiring across 16 countries", and the
    // standards strip below it repeated the six cards above it.
    $html = aboutHtml();

    expect($html)->not->toContain('states-chips')
        ->not->toContain('press-strip')
        ->not->toContain('Hiring across')
        ->not->toContain('Browse top-paying jobs by state');
});

it('keeps every country box down to something that fits in it', function () {
    // The country cells held all sixteen names, so the card ran past its
    // neighbours in the grid and the hero float wrapped to six lines.
    $html = aboutHtml();

    $coverage = app(App\Services\SiteCoverage::class);

    expect($html)->toContain($coverage->topList(2))
        // "The " in front of a list that already writes "the USA".
        ->not->toContain('The the ');
});
