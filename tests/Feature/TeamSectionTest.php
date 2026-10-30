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

it('no longer prints the standards strip and shows the founder photo', function () {
    $this->get('/about-us')
        ->assertOk()
        ->assertDontSee('aria-label="Standards"', false)
        ->assertSee('public/team/muhammad-asif.jpg', false);
});

it('shows Ali Rehman with his photo and Abdullah Zeheer with initials', function () {
    expect(file_exists(public_path('team/ali-rehman.jpg')))->toBeTrue();

    $this->get('/about-us')
        ->assertOk()
        ->assertSee('public/team/ali-rehman.jpg', false)
        ->assertSee('Abdullah Zeheer');
});

it('explains that JobGader posts verified jobs, does not give jobs and sells CV writing', function () {
    $this->get('/about-us')
        ->assertOk()
        ->assertSee('We post verified jobs')
        ->assertSee('We do not give jobs')
        ->assertSee('CV writing is a paid service')
        ->assertDontSee('no fee at any stage')
        ->assertDontSee('We will never ask you for money, at any stage');
});

it('does not show a stock photograph captioned as the JobGader team', function () {
    $this->get('/about-us')->assertOk()->assertDontSee('about-founders.jpg', false);
});
