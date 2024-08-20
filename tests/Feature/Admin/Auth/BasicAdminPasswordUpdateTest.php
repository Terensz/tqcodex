<?php

namespace Tests\Feature\Admin\Auth;

use Domain\Admin\Models\User;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $oldPassword = 'TestAdmin9876543';
    $hashedOldPassword = Hash::make($oldPassword);
    $newPassword = 'Alma123412341212';

    $user = User::factory()->createUntilNotTaken();
    $user->password = $hashedOldPassword;
    $user->save();
    $user = $user->refresh();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    $putParams = [
        // 'current_password' => 'TestAdmin9876543',
        'current_password' => $oldPassword,
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ];

    $response = $this
        ->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        // ->from('/profile')
        ->put(route('admin.password.update', ['access_token' => $accessToken]), $putParams);

    $response
        ->assertSessionHasNoErrors();

    $this->assertTrue(Hash::check($newPassword, $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $oldPassword = 'TestAdmin9876543';
    $hashedOldPassword = Hash::make($oldPassword);
    $newPassword = 'Alma123412341212';

    $user = User::factory()->createUntilNotTaken();
    $user->password = $hashedOldPassword;
    $user->save();
    $user = $user->refresh();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    // $response = $this
    //     ->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
    //     ->from('/profile')
    //     ->put('/password', [
    //         'current_password' => 'wrong-password',
    //         'password' => 'new-password',
    //         'password_confirmation' => 'new-password',
    //     ]);

    $putParams = [
        // 'current_password' => 'TestAdmin9876543',
        'current_password' => 'wrong-password',
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ];

    $response = $this
        ->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        // ->from('/profile')
        ->put(route('admin.password.update', ['access_token' => $accessToken]), $putParams);

    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');
    // ->assertRedirect('/profile')
});
