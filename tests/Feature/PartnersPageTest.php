<?php

use function Pest\Laravel\get;

it('serves the partners page', function () {
    get(route('partners'))->assertOk()->assertSee('The Institutions We Work With', false);
});

it('is reachable from the footer of every page', function (string $url) {
    get($url)->assertOk()->assertSee('href="'.route('partners').'">Partners</a>', false);
})->with([
    'home' => '/',
    'jobs' => '/jobs',
    'about' => '/about-us',
]);

it('states plainly what a memorandum of understanding does not promise', function () {
    $text = html_entity_decode(strip_tags(get(route('partners'))->assertOk()->getContent()));

    expect($text)->toContain('not an employer, a recruiter or a visa agent')
        ->toContain('No partner guarantees you a job')
        ->toContain('We are not paid to list anyone here');
});

it('renders the same eight questions in the FAQ and its FAQPage schema', function () {
    $html = get(route('partners'))->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

    $faq = collect($blocks[1])
        ->map(fn (string $json): ?array => json_decode(trim($json), true))
        ->first(fn (?array $node): bool => ($node['@type'] ?? null) === 'FAQPage');

    preg_match_all('#<summary>(.*?)</summary>#s', $html, $summaries);

    $visible = array_map(
        fn (string $question): string => html_entity_decode(trim($question), ENT_QUOTES),
        $summaries[1]
    );

    expect($faq['mainEntity'])->toHaveCount(8)
        ->and(array_column($faq['mainEntity'], 'name'))->toBe($visible);
});

it('carries a canonical url and the banner in its social cards', function () {
    $html = get(route('partners'))->assertOk()->getContent();

    expect($html)->toContain('rel="canonical" href="'.route('partners').'"')
        ->toContain('user/images/partners-banner.jpg');
});

it('ships the banner the page points at', function () {
    expect(file_exists(public_path('user/images/partners-banner.jpg')))->toBeTrue();
});

it('appears in the sitemap', function () {
    // /sitemap.xml is an index; the static pages live in the core sitemap.
    get('/sitemap-core.xml')->assertOk()->assertSee(url('/partners'), false);
});

it('hides the partner list until there are partners to name', function () {
    // The page ships with an empty list, and a heading over nothing reads worse
    // than no heading. The section and its ItemList schema only appear once the
    // signed institutions are in.
    $html = get(route('partners'))->assertOk()->getContent();

    expect($html)->not->toContain('class="pt-hero-count"')
        ->not->toContain('class="pt-grid"')
        ->not->toContain('"ItemList"');
});

it('lays the four explainer cards out in one row, in the resume page design', function () {
    // The owner asked for the treatment the resume page gives its cards: an
    // icon tile over the heading, and all four across a single row.
    $html = get(route('partners'))->assertOk()->getContent();

    expect($html)->toContain('.pt-points { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }')
        ->and(substr_count($html, 'class="pt-point-ico"'))->toBe(4)
        ->and(substr_count($html, 'class="pt-point"'))->toBe(4);
});
