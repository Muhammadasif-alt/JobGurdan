<?php

use function Pest\Laravel\get;

it('shows both contact addresses as mailto links', function () {
    $response = get(route('contact.us'));

    $response->assertOk()
        ->assertSee('mailto:info@jobgader.com', false)
        ->assertSee('mailto:admin@jobgader.com', false);
});

it('lists the admin address in the organisation contact points', function () {
    $html = get(route('contact.us'))->assertOk()->getContent();

    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);

    $contactPoints = collect($matches[1])
        ->map(fn (string $json): ?array => json_decode(trim($json), true))
        ->filter()
        ->firstWhere('@type', 'Organization')['contactPoint'] ?? [];

    expect($contactPoints)->not->toBeEmpty()
        ->and(array_column($contactPoints, 'email'))
        ->toContain('info@jobgader.com', 'admin@jobgader.com');
});
