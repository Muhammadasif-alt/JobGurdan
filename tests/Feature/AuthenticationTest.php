<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function (string $role, string $dashboard) {
    // This site does not have one dashboard. LoginResponse dispatches on the
    // user's role, and a user with no role lands on the public home page
    // rather than looping through an auth-only /dashboard.
    $user = User::factory()->create(['role' => $role]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route($dashboard, absolute: false));
})->with([
    'job seeker' => [User::ROLE_JOB_SEEKER, 'seeker.dashboard'],
    'company' => [User::ROLE_COMPANY, 'company.dashboard'],
    'admin' => [User::ROLE_ADMIN, 'admin.dashboard'],
    'no role' => [User::ROLE_USER, 'home'],
]);

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
