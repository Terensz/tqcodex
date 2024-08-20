<?php

use Domain\Customer\Models\Contact;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Hash;

/*
Standalone run:
php artisan test tests/Feature/Customer/Auth/BasicCustomerPasswordUpdateTest.php
*/

test('password can be updated', function () {
    $oldPassword = 'TestCustomer9876543';
    $hashedOldPassword = Hash::make($oldPassword);
    $newPassword = 'Alma123412341212';

    $user = Contact::factory()->createUntilNotTaken();
    $user->password = $hashedOldPassword;
    // $user->fillEmailVerifiedAtWithCurrentTime();
    $user->save();
    $user = $user->refresh();

    // dump($user->getContactProfile());

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);

    $putParams = [
        // 'current_password' => 'TestCustomer9876543',
        'current_password' => $oldPassword,
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ];

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /** @phpstan-ignore-next-line  */
    $response = $this->put(route('customer.password.update', ['access_token' => $accessToken]), $putParams);
    $response->assertSessionHasNoErrors();

    $user->refresh();

    /** @phpstan-ignore-next-line  */
    $this->assertTrue(Hash::check($newPassword, $user->password));
});

test('correct password must be provided to update password', function () {
    $oldPassword = 'TestCustomer9876543';
    $hashedOldPassword = Hash::make($oldPassword);
    $newPassword = 'Alma123412341212';

    $user = Contact::factory()->createUntilNotTaken();
    $user->password = $hashedOldPassword;
    // $user->email_verified_at = (string)now();
    $user->save();
    $user = $user->refresh();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);

    $putParams = [
        // 'current_password' => 'TestCustomer9876543',
        'current_password' => 'wrong-password',
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ];

    /** @phpstan-ignore-next-line  */
    $response = $this
        ->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
        // ->from('/profile')
        ->put(route('customer.password.update', ['access_token' => $accessToken]), $putParams);

    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');
    // ->assertRedirect('/profile')
});
