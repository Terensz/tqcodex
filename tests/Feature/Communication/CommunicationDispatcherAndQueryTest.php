<?php

use Domain\Customer\Models\Contact;
use Domain\Project\Mails\PartnerInviteMail;
use Domain\Shared\Helpers\CommunicationDispatcher;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Mail;

/*
Standalone run:
php artisan test tests/Feature/Customer/Project/CommunicationDispatcherAndQueryTest.php
*/

test('Testing CommunicationDispatcher and CommunicationDispatchProcessBuilder', function () {
    Mail::fake();

    $user = Contact::factory()->createUntilNotTaken();
    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $currentContactProfile = UserService::getContactProfile();
    if (! $currentContactProfile) {
        throw new \Exception('No contact logged in!');
    }

    $senderAddress = $currentContactProfile->email;
    $senderName = $currentContactProfile->getName();

    // Step 1: creating some communication records.
    for ($looper = 0; $looper < 20; $looper++) {
        $emailDispatcher = new CommunicationDispatcher;
        $emailDispatcher->campaignReferenceCode = PartnerInviteMail::REFERENCE_CODE;
        $emailDispatcher->campaignTitleLangRef = PartnerInviteMail::TITLE_LANG_REF;
        $emailDispatcher->campaignIsListable = false;
        $emailDispatcher->campaignIsEditable = false;
        $emailDispatcher->campaignIsRedispatchable = false;
        $emailDispatcher->dispatchProcessSenderContactId = UserService::getContact() ? UserService::getContact()->id : null;
        $emailDispatcher->init();
        $subject = 'Test subject '.$looper;
        $bodyMain = 'Test body main '.$looper;
        $recipientEmail = 'terencecleric@gmail.com';
        $recipientName = 'Papp Ferenc';
        $mailable = new PartnerInviteMail($subject, $bodyMain, [$recipientEmail, $recipientName], [$senderAddress, $senderName]);
        $emailDispatcher->logAndSend($mailable);
        $emailDispatcher->finish();
    }

    Mail::assertSent(PartnerInviteMail::class);
});
