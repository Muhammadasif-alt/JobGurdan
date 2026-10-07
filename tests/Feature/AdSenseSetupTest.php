<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('loads the AdSense script in the public head', function () {
    get('/')
        ->assertOk()
        ->assertSee('pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1328233902524444', false);
});

it('ships ads.txt in both public roots with the publisher line', function (string $path) {
    expect(trim(file_get_contents(base_path($path))))
        ->toBe('google.com, pub-1328233902524444, DIRECT, f08c47fec0942fa0');
})->with(['public/ads.txt', 'public/public/ads.txt']);
