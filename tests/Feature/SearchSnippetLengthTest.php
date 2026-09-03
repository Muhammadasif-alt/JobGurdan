<?php

use Database\Seeders\CleanerJobsSaudiBlogSeeder;
use Database\Seeders\ConstructionUsaBlogSeeder;
use Database\Seeders\DriverJobsSaudiBlogSeeder;

use function Pest\Laravel\get;

/**
 * Titles and descriptions have to fit what Google will actually display, and
 * the country list inside them grows on its own as listings reach new
 * countries — so the limits need asserting, not assuming.
 */
const TITLE_LIMIT = 62;

const DESCRIPTION_LIMIT = 160;

function snippet(string $path): array
{
    $html = get($path)->assertOk()->getContent();

    preg_match('#<title>(.*?)</title>#si', $html, $title);
    preg_match('#<meta name="description" content="(.*?)"#si', $html, $description);

    return [
        'title' => html_entity_decode(trim($title[1] ?? ''), ENT_QUOTES),
        'description' => html_entity_decode(trim($description[1] ?? ''), ENT_QUOTES),
    ];
}

beforeEach(function () {
    $this->seed(DriverJobsSaudiBlogSeeder::class);
    $this->seed(CleanerJobsSaudiBlogSeeder::class);
    $this->seed(ConstructionUsaBlogSeeder::class);
});

it('keeps every public page inside the search snippet limits', function (string $path) {
    ['title' => $title, 'description' => $description] = snippet($path);

    expect($title)->not->toBeEmpty()
        ->and($description)->not->toBeEmpty()
        ->and(mb_strlen($title))->toBeLessThanOrEqual(TITLE_LIMIT)
        ->and(mb_strlen($description))->toBeLessThanOrEqual(DESCRIPTION_LIMIT);
})->with([
    '/', '/jobs', '/blog', '/companies', '/about-us', '/contact-us',
    '/categories', '/locations', '/remote-jobs-usa', '/it-jobs',
    '/privacy-policy', '/terms-of-service',
    '/blog/cleaner-jobs-in-saudi-arabia-for-foreigners',
    '/jobs/cleaner-hotel-hospital-office-and-residential-saudi-arabia',
]);

it('leads a job title with the role rather than the whole category list', function () {
    // The position doubles as a category list, which produced a 131-character
    // title before it was cut back to the part in front of the dash.
    ['title' => $title] = snippet('/jobs/cleaner-hotel-hospital-office-and-residential-saudi-arabia');

    expect($title)->toStartWith('Cleaner')
        ->toContain('Saudi Arabia')
        ->toEndWith('| JobGader');
});

it('trims an over-long description at a word boundary rather than mid-word', function () {
    ['description' => $description] = snippet('/');

    expect($description)->not->toEndWith(' ')
        ->and(substr_count($description, '  '))->toBe(0);
});
