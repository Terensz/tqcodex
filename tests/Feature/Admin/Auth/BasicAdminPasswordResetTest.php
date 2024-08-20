<?php

namespace Tests\Feature\Admin\Auth;

use Domain\Admin\Models\User;
use Domain\Communication\Mails\ResetAdminPasswordNotification;
// use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

/*
Standalone run:
php artisan test tests/Feature/Admin/Auth/BasicAdminPasswordResetTest.php
*/

test('reset password link screen can be rendered', function () {
    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('admin.password.request'));

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->post(route('admin.password.email'), ['email' => $user->email]);

    Notification::assertSentTo([$user], ResetAdminPasswordNotification::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->post(route('admin.password.email'), ['email' => $user->email]);
    Notification::assertSentTo([$user], ResetAdminPasswordNotification::class, function ($notification) {
        $urlParts = explode('/', $notification->getUrl());
        $token = $urlParts[count($urlParts) - 1];
        /** @phpstan-ignore-next-line  */
        $response = $this->get(route('admin.password.reset', ['token' => $token]));
        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->post(route('admin.password.email'), ['email' => $user->email]);

    Notification::assertSentTo([$user], ResetAdminPasswordNotification::class, function ($notification) use ($user) {
        $urlParts = explode('/', $notification->getUrl());
        $token = $urlParts[count($urlParts) - 1];
        /** @phpstan-ignore-next-line  */
        $response = $this->post(route('admin.password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Password12345678',
            'password_confirmation' => 'Password12345678',
        ]);

        $response->assertSessionHasNoErrors();

        return true;
    });
});
