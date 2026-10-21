<?php

it('shows the three team members and the owner on the About page', function () {
    $this->get('/about-us')
        ->assertOk()
        ->assertSee('Muhammad Asif')
        ->assertSee('Founder and Editor')
        ->assertSee('Ali Rehman')
        ->assertSee('Content Writer')
        ->assertSee('Abdullah Zeheer')
        ->assertSee('Social Media Manager')
        ->assertSee('from Pakistan');
});
