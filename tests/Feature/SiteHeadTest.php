<?php

use function Pest\Laravel\get;

it('keeps the Search Console verification tag in the site head', function () {
    get('/')->assertOk()
        ->assertSee('name="google-site-verification" content="NEZhtXbrZZkQYcz5kQO1hT17Vs27bb3VYUgrjUTUeQ0"', false);
});
