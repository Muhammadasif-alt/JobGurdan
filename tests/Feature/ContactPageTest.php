<?php

use function Pest\Laravel\get;

it('shows the single contact address as a mailto link', function () {
    $response = get(route('contact.us'));

    $response->assertOk()
        ->assertSee('mailto:'.config('site.contact_email'), false)
        ->assertDontSee('info@jobgader.com')
        ->assertDontSee('support@jobgader.com');
});

it('lists the contact address in the organisation contact points', function () {
    $html = get(route('contact.us'))->assertOk()->getContent();

    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);

    $contactPoints = collect($matches[1])
        ->map(fn (string $json): ?array => json_decode(trim($json), true))
        ->filter()
        ->firstWhere('@type', 'Organization')['contactPoint'] ?? [];

    expect($contactPoints)->not->toBeEmpty()
        ->and(array_column($contactPoints, 'email'))
        ->toContain(config('site.contact_email'));
});
