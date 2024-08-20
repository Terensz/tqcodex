<?php

namespace Tests\Feature\Admin\Auth;

use Domain\Admin\Models\User;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Admin/Auth/BasicAdminAuthenticationTest.php
*/

test('login screen can be rendered', function () {
    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('admin.login'));

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $response = $this->post(route('admin.loginpost'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    /** @phpstan-ignore-next-line  */
    $this->assertAuthenticated(UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));
    $response->assertRedirect(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->post(route('admin.loginpost'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    /** @phpstan-ignore-next-line  */
    $this->assertGuest(UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));
});

test('users can logout', function () {
    $user = User::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user);

    /** @phpstan-ignore-next-line  */
    $response = $this->post(route('admin.logout'));

    /** @phpstan-ignore-next-line  */
    $this->assertGuest(UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));
    $response->assertRedirect(route('admin.login'));
});
