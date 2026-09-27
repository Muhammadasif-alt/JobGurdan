<?php

use App\Models\User;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration screen cannot be rendered if support is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
})->skip(function () {
    return Features::enabled(Features::registration());
}, 'Registration support is enabled.');

test('new users can register', function () {
    // CreateNewUser asks for a username and an account type on top of the
    // Jetstream fields; without them validation fails and nobody is signed in.
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'test_user',
        'email' => 'test@example.com',
        'role' => User::ROLE_JOB_SEEKER,
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $this->assertAuthenticated();
    // Registration keeps Fortify's /dashboard, which then dispatches on role.
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->get('/dashboard')->assertRedirect(route('seeker.dashboard'));

    expect(User::query()->where('email', 'test@example.com')->firstOrFail())
        ->username->toBe('test_user')
        ->role->toBe(User::ROLE_JOB_SEEKER);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration refuses an account with no type chosen', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'username' => 'test_user',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ])->assertSessionHasErrors('role');

    $this->assertGuest();
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');
