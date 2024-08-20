<?php

declare(strict_types=1);

use Domain\Admin\Models\User;
use Domain\Admin\Models\UserToken;
use Domain\Communication\Mails\AdminEmailChangeRequestNotification;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Notification;

/*
Standalone run:
php artisan test tests/Feature/Admin/Profile/UserEmailChangeTest.php
*/

test('As Admin: testing profile - changing e-mail.', function () {
    Notification::fake();

    $originalEmail = 'oldtestuser@almail.com';
    $newEmailRequest = 'newtestuser@almail.com';
    $password = 'Alma12341234';
    $firstName = 'OldTest';
    $lastname = 'OldUser';

    $user = User::factory()->createUntilNotTaken([
        'firstname' => $firstName,
        'lastname' => $lastname,
        'email' => $originalEmail,
        'password' => $password,
    ], [
        'password',
    ]);
    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('admin.profile.edit', ['access_token' => $accessToken]));
    $response->assertStatus(200);

    // Submit the form with new email
    /** @phpstan-ignore-next-line  */
    $response = $this->patch(route('admin.profile.update', ['access_token' => $accessToken]), [
        'firstname' => $firstName,
        'lastname' => $lastname,
        'email' => $newEmailRequest,
    ]);

    Notification::assertSentTo([$user], AdminEmailChangeRequestNotification::class, function ($notification) {
        return true;
    });

    $userToken = UserToken::where(['email' => $newEmailRequest, 'token_type' => UserToken::TOKEN_TYPE_EMAIL_CHANGE])->first();
    expect($userToken)->toBeObject();

    /**
     * And now: "clicking" the link in the e-mail
     */
    /** @phpstan-ignore-next-line  */
    $response = $this
        ->get(route('admin.email-change.create', [
            'token' => $userToken->token,
        ]));

    $response->assertStatus(200);

    /**
     * Submitting the form
     */
    /** @phpstan-ignore-next-line  */
    $response = $this
        ->post(route('admin.email-change.store'), [
            'token' => $userToken->token,
            'old_email' => $originalEmail,
            'email' => $newEmailRequest,
            'password' => $password,
        ]);

    $response->assertRedirectToRoute('admin.profile.edit', ['access_token' => $accessToken]);

    $user = $user->refresh();
    
    /** @phpstan-ignore-next-line  */
    $this->assertSame($newEmailRequest, $user->email);
});
