<?php

declare(strict_types=1);

use Domain\Admin\Models\User;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Admin/Profile/UserProfileTest.php
*/

uses()->group('admin-basic');

test('As Admin: Profile screen can be displayed.', function () {
    $user = User::factory()->createUntilNotTaken();
    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('admin.profile.edit', ['access_token' => $accessToken]));

    $response->assertStatus(200);
});

test('profile information can be updated', function () {
    $user = User::factory()->createUntilNotTaken();
    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user);
    $originalEmail = $user->email;

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    /** @phpstan-ignore-next-line  */
    $response = $this
        ->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        ->patch(route('admin.profile.update', ['access_token' => $accessToken]), [
            'lastname' => 'Test',
            'firstname' => 'User',
            'email' => 'testuser@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.profile.edit', ['access_token' => $accessToken]));

    $user->refresh();

    /** @phpstan-ignore-next-line  */
    $this->assertSame('Test', $user->lastname);
    /** @phpstan-ignore-next-line  */
    $this->assertSame('User', $user->firstname);
    /** @phpstan-ignore-next-line  */
    $this->assertSame($originalEmail, $user->email);
    /** @phpstan-ignore-next-line  */
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->createUntilNotTaken();
    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    /** @phpstan-ignore-next-line  */
    $response = $this
        ->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        ->patch(route('admin.profile.update', ['access_token' => $accessToken]), [
            'lastname' => 'Test',
            'firstname' => 'User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.profile.edit', ['access_token' => $accessToken]));

    /** @phpstan-ignore-next-line  */
    $this->assertNotNull($user->refresh()->email_verified_at);
});
