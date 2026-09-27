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

it('names every institution that has signed, and counts them honestly', function () {
    $html = get(route('partners'))->assertOk()->getContent();

    expect($html)->toContain('class="pt-grid"')
        ->toContain('Rescue 1122 Lodhran')
        ->toContain('Punjab Police Lodhran')
        ->toContain('City Traffic Police Lodhran')
        // The hero count is read off the list, so it cannot drift from it.
        ->toContain('3 signed memoranda of understanding');

    expect(substr_count($html, 'class="pt-card"'))->toBe(3);
});

it('shows each partner its own crest, and ships the file', function (string $logo) {
    expect(file_exists(public_path('user/images/'.$logo)))->toBeTrue();

    get(route('partners'))->assertOk()->assertSee('user/images/'.$logo, false);
})->with([
    'rescue 1122' => 'partner-rescue-1122.png',
    'punjab police' => 'partner-punjab-police.png',
    'city traffic police' => 'partner-city-traffic-police.png',
]);

it('links each partner to its own official government site and nowhere else', function () {
    // The standing rule on this site: an institution is linked at its own
    // domain, never through an aggregator or a page carrying a job id.
    $html = get(route('partners'))->assertOk()->getContent();

    preg_match_all('#<div class="pt-card-foot">.*?</div>#s', $html, $feet);

    expect($feet[0])->toHaveCount(3);

    foreach ($feet[0] as $foot) {
        preg_match('#href="([^"]+)"#', $foot, $m);

        expect($m[1] ?? '')->toMatch('#^https://(www\.)?(rescue|punjabpolice)\.gov\.pk/$#');
    }
});

it('states the signing date where there is one, and does not invent one where there is not', function () {
    $html = get(route('partners'))->assertOk()->getContent();

    expect($html)->toContain('MOU signed 2 October 2023')
        ->toContain('MOU signed 7 October 2025')
        // City Traffic Police came in without a date; the badge just says signed.
        ->toContain('MOU signed</span>');
});

it('lists the partners in its ItemList schema, with the same names and urls', function () {
    $html = get(route('partners'))->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

    $list = collect($blocks[1])
        ->map(fn (string $json): ?array => json_decode(trim($json), true))
        ->first(fn (?array $node): bool => ($node['@type'] ?? null) === 'ItemList');

    expect($list)->not->toBeNull()
        ->and($list['numberOfItems'])->toBe(3)
        ->and(array_column(array_column($list['itemListElement'], 'item'), 'name'))->toBe([
            'Rescue 1122 Lodhran',
            'Punjab Police Lodhran',
            'City Traffic Police Lodhran',
        ]);
});

it('lays the four explainer cards out in one row, in the resume page design', function () {
    // The owner asked for the treatment the resume page gives its cards: an
    // icon tile over the heading, and all four across a single row.
    $html = get(route('partners'))->assertOk()->getContent();

    expect($html)->toContain('.pt-points { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }')
        ->and(substr_count($html, 'class="pt-point-ico"'))->toBe(4)
        ->and(substr_count($html, 'class="pt-point"'))->toBe(4);
});
