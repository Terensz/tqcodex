<?php

namespace Tests\Feature\Auth;

use Domain\Customer\Models\Contact;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Customer/Auth/BasicCustomerAuthenticationTest.php
*/

test('customer login screen can be rendered', function () {
    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('customer.login'));

    $response->assertStatus(200);
});

test('customers can authenticate using the login screen', function () {
    $user = Contact::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $response = $this->post(route('customer.loginpost'), [
        'email' => $user->getEmail(),
        'password' => 'password',
    ]);

    /** @phpstan-ignore-next-line  */
    $this->assertAuthenticated(UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));
    $response->assertRedirect(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER));
});

test('customers can not authenticate with invalid password', function () {
    $user = Contact::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->post(route('customer.loginpost'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    /** @phpstan-ignore-next-line  */
    $this->assertGuest(UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));
});

test('users can logout', function () {
    $user = Contact::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $response = $this->actingAs($user)->post(route('admin.logout'));

    /** @phpstan-ignore-next-line  */
    $this->assertGuest(UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));
    $response->assertRedirect(route('admin.login'));
});
// ->skip()
