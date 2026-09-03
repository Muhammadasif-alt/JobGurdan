<?php

use App\Models\User;

it('serves the admin panel under JobGader branding, not the template it came from', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $html = $this->actingAs($admin)->get('/administration')->assertOk()->getContent();

    expect($html)
        ->toContain('<title>JobGader Admin</title>')
        ->toContain('user/images/favicon.png')
        ->toContain('name="robots" content="noindex, nofollow"')
        // "JobsListing" is the AdminLTE demo name and "JU" is Jobs in USA.
        ->not->toContain('JobsListing')
        ->not->toContain('>JU<')
        ->not->toContain('content="AdminLTE');
});

it('ships a favicon.ico that is a real icon rather than an empty file', function () {
    $ico = public_path('favicon.ico');

    expect(file_exists($ico))->toBeTrue()
        ->and(filesize($ico))->toBeGreaterThan(1000);

    // ICONDIR: reserved 0, type 1 (icon), one image entry.
    $header = unpack('vreserved/vtype/vcount', file_get_contents($ico, false, null, 0, 6));

    expect($header['reserved'])->toBe(0)
        ->and($header['type'])->toBe(1)
        ->and($header['count'])->toBe(1);
});
