<?php

use Domain\Communication\Mails\ResetCustomerPasswordNotification;
use Domain\Customer\Models\Contact;
// use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

/*
Standalone run:
php artisan test tests/Feature/Customer/Auth/BasicCustomerPasswordResetTest.php
*/

test('reset password link screen can be rendered', function () {

    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('customer.password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {

    $contact = Contact::factory()->createUntilNotTaken();

    Notification::fake();

    /** @phpstan-ignore-next-line  */
    $this->post(route('customer.password.email'), ['email' => $contact->getContactProfile()->email]);

    Notification::assertSentTo([$contact->getContactProfile()], ResetCustomerPasswordNotification::class);
});

test('password can be reset with valid token', function () {

    $contact = Contact::factory()->createUntilNotTaken();

    Notification::fake();

    /** @phpstan-ignore-next-line  */
    $this->post(route('customer.password.email', ['email' => $contact->getContactProfile()->email]))->assertRedirect();

    Notification::assertSentTo([$contact->getContactProfile()], ResetCustomerPasswordNotification::class, function ($notification) {
        return true;
    });
});
