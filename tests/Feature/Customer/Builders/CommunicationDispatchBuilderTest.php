<?php

use Database\Factories\Customer\ContactFactory;
use Domain\Communication\Enums\CommunicationDispatchProcessStatus;
use Domain\Communication\Enums\CommunicationForm;
use Domain\Communication\Models\CommunicationCampaign;
use Domain\Communication\Models\CommunicationDispatch;
use Domain\Communication\Models\CommunicationDispatchProcess;
use Domain\Customer\Models\Contact;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Customer/Builders/CommunicationDispatchBuilderTest.php
*/

uses()->group('builder');

test('that CommunicationDispatch builder finds valid CommunicationDispatch', function () {

    $validCommunicationDispatches = CommunicationDispatch::valid()->get();

    expect($validCommunicationDispatches)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});

test('that CommunicationDispatch which has recipient Contact is listable by hasRecipientContact() method', function () {

    /**
     * Creating a sender Contact
     */
    $contact1 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => 'test.contact.125@almail.com',
        'email_verified_at' => now(),
    ]);
    expect($contact1)->toBeObject();
    
    if (! $contact1 instanceof Contact) {
        $contact1 = null;
    }

    expect($contact1->getContactProfile())->toBeObject();
    expect($contact1 ? $contact1->id : null)->toBeNumeric();

    /**
     * Creating a recipient Contact
     */
    $contact2 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test126',
        'lastname' => 'Contact',
        'email' => 'test.contact.126@almail.com',
        'email_verified_at' => now(),
    ]);
    expect($contact2)->toBeObject();

    if (! $contact2 instanceof Contact) {
        $contact2 = null;
    }
    
    expect($contact2->getContactProfile())->toBeObject();
    expect($contact2 ? $contact2->id : null)->toBeNumeric();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    /**
     * Creating a full Communication
     * - creating a CommunicationCampaign
     * - creating a CommunicationDispatchProcess
     * - creating a CommunicationDispatch
     */
    $communicationCampaign1 = new CommunicationCampaign([
        'reference_code' => 'campaing_125',
        'title_lang_ref' => 'project.Campaing125',
        // 'raw_subject' => 'Test Campaign 125',
        // 'raw_body' => 'Dear Recipient, this is the Test Campaign 125. Regards: Team',
        'is_published' => true,
        'is_listable' => true,
        'is_editable' => true,
        'is_redispatchable' => true,
    ]);
    $communicationCampaign1->save();
    $communicationCampaign1->refresh();
    expect($communicationCampaign1)->toBeObject();
    expect($communicationCampaign1->id)->toBeNumeric();

    $communicationDispatchProcess1 = new CommunicationDispatchProcess([
        'communicationcampaign_id' => $communicationCampaign1->id,
        'sender_contact_id' => $contact1->id,
        'communication_form' => CommunicationForm::EMAIL->value,
        'status' => CommunicationDispatchProcessStatus::IN_PROGRESS->value,
    ]);
    $communicationDispatchProcess1->save();
    $communicationDispatchProcess1->refresh();
    expect($communicationDispatchProcess1)->toBeObject();
    expect($communicationDispatchProcess1->id)->toBeNumeric();

    $communicationDispatchData1 = [
        'communicationdispatchprocess_id' => $communicationDispatchProcess1->id,
        'sender_address' => $contact1->getContactProfile()->email,
        'sender_name' => $contact1->getContactProfile()->getFullName(),

        'recipient_address' => 'test.contact.126@almail.com',
        'recipient_name' => 'Test126 Contact',
        'subject' => 'Test Campaign 125',
        'body' => 'Dear Recipient, this is the Test Campaign 125. Regards: Team',
    ];
    $communicationDispatch1 = new CommunicationDispatch($communicationDispatchData1);
    $communicationDispatch1->save();
    $communicationDispatch1->refresh();
    expect($communicationDispatch1)->toBeObject();
    expect($communicationDispatch1->id)->toBeNumeric();

    /**
     * Creating ANOTHER full Communication
     * - creating a CommunicationCampaign
     * - creating a CommunicationDispatchProcess
     * - creating a CommunicationDispatch
     */
    $communicationCampaign2 = new CommunicationCampaign([
        'reference_code' => 'campaing_126',
        'title_lang_ref' => 'project.Campaing126',
        // 'raw_subject' => 'Test Campaign 125',
        // 'raw_body' => 'Dear Recipient, this is the Test Campaign 125. Regards: Team',
        'is_published' => true,
        'is_listable' => true,
        'is_editable' => true,
        'is_redispatchable' => true,
    ]);
    $communicationCampaign2->save();
    $communicationCampaign2->refresh();
    expect($communicationCampaign2)->toBeObject();
    expect($communicationCampaign2->id)->toBeNumeric();

    $communicationDispatchProcess2 = new CommunicationDispatchProcess([
        'communicationcampaign_id' => $communicationCampaign2->id,
        'sender_contact_id' => $contact1->id,
        'communication_form' => CommunicationForm::EMAIL->value,
        'status' => CommunicationDispatchProcessStatus::IN_PROGRESS->value,
    ]);
    $communicationDispatchProcess2->save();
    $communicationDispatchProcess2->refresh();
    expect($communicationDispatchProcess2)->toBeObject();
    expect($communicationDispatchProcess2->id)->toBeNumeric();

    $communicationDispatchData2 = [
        'communicationdispatchprocess_id' => $communicationDispatchProcess2->id,
        'sender_address' => $contact1->getContactProfile()->email,
        'sender_name' => $contact1->getContactProfile()->getFullName(),

        'recipient_address' => 'test.contact.126@almail.com',
        'recipient_name' => 'Test126 Contact',
        'subject' => 'Test Campaign 126',
        'body' => 'Dear Recipient, this is the Test Campaign 126. Regards: Team',
    ];
    $communicationDispatch2 = new CommunicationDispatch($communicationDispatchData2);
    $communicationDispatch2->save();
    $communicationDispatch2->refresh();
    expect($communicationDispatch2)->toBeObject();
    expect($communicationDispatch2->id)->toBeNumeric();

    // dump($communicationDispatchData);
    // dump($communicationDispatch);

    // $currentOrgsBoundsContactProfile = Organization::hasContactProfiles()->find($org->id);
    // $this->assertEquals($org->id, $currentOrgsBoundsContactProfile->id);

    /**
     * "Logging in"
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * Assertions.
     *
     * Now we have a Contact who is actually logged in. He dispatched 2 e-mails so far.
     *
     * Our assertions will be:
     * - Contact must see every mails he sent.
     * - Contact must NOT see any mails where the sender is NOT he.
     * - If the list is filtered to a certain value of CommunicationDispatchProcess id, than we must only see those Dispatches which were sent with that CommunicationDispatchProcess.
     */

    /**
     * FIRST list: we list all the mails.
     */

    // dump (nl2br(CommunicationDispatch::listableByCustomer()->toSql()));exit;

    $list = CommunicationDispatch::listableByCustomer()->get();

    $countOfMails = 0;
    $allMailsSenderIsCurrentContact = true;
    foreach ($list as $listElement) {
        $communicationDispatchProcess = $listElement->communicationDispatchProcess()->first();
        if ($communicationDispatchProcess->sender_contact_id != $contact1->id) {
            $allMailsSenderIsCurrentContact = false;
        }

        $countOfMails++;
    }

    /** @phpstan-ignore-next-line  */
    $this->assertTrue($allMailsSenderIsCurrentContact);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals(2, $countOfMails);

    /**
     * SECOND list: we list ONLY the mails from the SECOND CommunicationDispatchProcess.
     */
    $list = CommunicationDispatch::listableByCustomer($communicationDispatchProcess2->id)->get();

    $countOfMails = 0;
    $allMailsSenderIsCurrentContact = true;
    foreach ($list as $listElement) {
        $communicationDispatchProcess = $listElement->communicationDispatchProcess()->first();
        if ($communicationDispatchProcess->sender_contact_id != $contact1->id) {
            $allMailsSenderIsCurrentContact = false;
        }

        $countOfMails++;
    }

    /** @phpstan-ignore-next-line  */
    $this->assertTrue($allMailsSenderIsCurrentContact);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals(1, $countOfMails);
});
