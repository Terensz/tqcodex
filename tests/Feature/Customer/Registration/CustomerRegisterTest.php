<?php

namespace Tests\Feature\Customer\Project;

use Domain\Customer\Enums\CorporateForm;
use Domain\Shared\Helpers\StringHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

/*
Standalone run:
php artisan test tests/Feature/Customer/Registration/CustomerRegisterTest.php
*/

uses()->group('project');

test('As guest: filling the register form.', function () {

    App::setLocale('hu');
    Notification::fake();
    Event::fake();

    $lastname = 'Test';
    $firstname = 'Customer';
    $email = 't.erence.cleric@gmail.com';
    $mobile = '+36705150551';
    $password = 'Zg6B3MnR4G3k45m1m34';
    $organization_name = 'Nagyanyáink Sütödéje Kft.';
    $organization_org_type = CorporateForm::LTD->value;
    $organization_taxpayer_id = '14675302-2-03';
    $organization_vat_banned = false;

    $test = Livewire::test(\Domain\Project\Livewire\ContactRegister::class, [
        'invitedRegister' => false,
        'partnerEmail' => null,
        'partnerName' => null,
        'partnerContact' => null,
    ])
        ->set('form.lastname', $lastname)
        ->set('form.firstname', $firstname)
        ->set('form.email', $email)
        ->set('form.mobile', $mobile)
        ->set('form.phone', $mobile)
        ->set('form.password', $password)
        ->set('form.organization_name', $organization_name)
        ->set('form.organization_org_type', $organization_org_type)
        ->set('form.organization_taxpayer_id', $organization_taxpayer_id)
        ->set('form.organization_vat_banned', $organization_vat_banned)
        ->call('save');

    $component = $test->instance();
    $contact = $component->form->contact;
    $contact->refresh();

    /**
     * At this point the software must send the VerifyEmail.
     */
    // Notification::assertSentTo([$contact->getContactProfile()], \Domain\Customer\Mails\VerifyEmail::class, function ($notification) {
    //     return true;
    // });

    Notification::assertSentTo([$contact->getContactProfile()], \Domain\Communication\Mails\VerifyCustomerRegistration::class, function ($notification, $channels, $notifiable) {
        $mailData = $notification->toMail($notifiable);
        // dump($mailData);

        /** @phpstan-ignore-next-line  */
        $this->verificationUrl = $mailData->actionUrl;

        return true;
    });

    /** @phpstan-ignore-next-line  */
    $this->assertStringContainsString('/verify-email/', $this->verificationUrl);

    /**
     * On the website, until the system sends the mail, we are logged in.
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact, UserService::GUARD_CUSTOMER);
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    /**
     * Until we did not confirm the registration, we must not have the permissions to be a real user.
     */
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);

    /**
     * Without verifying the customer (email_verified_at field contains date) we should be logged out immediately from the entire customer area.
     */
    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('customer.dashboard', ['access_token' => $accessToken]));
    $response->assertDontSee(__('user.ProfileAndLogout'));

    /**
     * First, we will try to scam the system with a fake signature.
     * We have to fail.
     */
    /** @phpstan-ignore-next-line  */
    $verificationUrlParts = explode('&signature=', $this->verificationUrl);
    $fakeSignature = StringHelper::generateRandomString(64, false, $verificationUrlParts[1]);
    $verificationUrlParts[1] = $fakeSignature;
    $fakeVerificationUrl = implode('&signature=', $verificationUrlParts);

    /** @phpstan-ignore-next-line  */
    $response = $this->get($fakeVerificationUrl);
    $gotInvalidSignatureException = (isset($response->exception) && $response->exception instanceof \Illuminate\Routing\Exceptions\InvalidSignatureException);

    /** @phpstan-ignore-next-line  */
    $this->assertTrue($gotInvalidSignatureException);

    /**
     * Clicking on the link in the mail.
     */
    /** @phpstan-ignore-next-line  */
    $response = $this->get($this->verificationUrl);

    /**
     * Impersonating $contact after refreshing.
     */
    $contact->refresh();

    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact, UserService::GUARD_CUSTOMER);
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    /**
     * And now we can log in as a customer.
     */
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    
    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('customer.dashboard', ['access_token' => $accessToken]));
    $response->assertSee(__('user.ProfileAndLogout'));
});
