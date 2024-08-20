<?php

use Database\Factories\Customer\ContactFactory;
use Domain\Communication\Enums\CommunicationDispatchProcessStatus;
use Domain\Communication\Enums\CommunicationForm;
use Domain\Communication\Models\CommunicationCampaign;
use Domain\Communication\Models\CommunicationDispatchProcess;
use Domain\Customer\Models\Contact;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Customer/Builders/CommunicationDispatchProcessBuilderTest.php
*/

uses()->group('builder');

test('that CommunicationDispatchProcess builder finds valid CommunicationDispatchProcess.', function () {

    $validCommunicationDispatches = CommunicationDispatchProcess::valid()->get();

    expect($validCommunicationDispatches)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('that CommunicationDispatchProcess which has sender Contact is listable by hasSenderContact() method.', function () {

    /**
     * Creating a sender Contact
     */
    $contact1Email = 'test.contact.125@almail.com';
    $contact1 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => $contact1Email,
        'email_verified_at' => now(),
    ]);

    expect($contact1)->toBeObject();

    if (! $contact1 instanceof Contact) {
        $contact1 = null;
    }

    expect($contact1->getContactProfile())->toBeObject();
    expect($contact1->id)->toBeNumeric();

    /**
     * Creating a Communication process.
     * - creating a CommunicationCampaign
     * - creating a CommunicationDispatchProcess
     */
    $communicationCampaign = new CommunicationCampaign([
        'reference_code' => 'campaing_125',
        'title_lang_ref' => 'project.Campaing125',
        'is_published' => true,
        'is_listable' => true,
        'is_editable' => true,
        'is_redispatchable' => true,
    ]);
    $communicationCampaign->save();
    $communicationCampaign->refresh();
    expect($communicationCampaign)->toBeObject();
    expect($communicationCampaign->id)->toBeNumeric();

    $communicationDispatchProcess = new CommunicationDispatchProcess([
        'communicationcampaign_id' => $communicationCampaign->id,
        'sender_contact_id' => $contact1->id,
        'communication_form' => CommunicationForm::EMAIL->value,
        'status' => CommunicationDispatchProcessStatus::IN_PROGRESS->value,
    ]);
    $communicationDispatchProcess->save();
    $communicationDispatchProcess->refresh();
    expect($communicationDispatchProcess)->toBeObject();
    expect($communicationDispatchProcess->id)->toBeNumeric();

    /**
     * Do the assertings.
     * - Searching a CommunicationDispatchProcess where the current Contact is the sender.
     * - Asserting if the found CommunicationDispatchProcess has the sender equal the current Contact.
     */
    $communicationDispatchProcess = CommunicationDispatchProcess::hasSenderContact()->where(['sender_contact_id' => $contact1->id])->first();
    expect($communicationDispatchProcess)->toBeObject();
    $senderContact = $communicationDispatchProcess->senderContact()->first();

    /** @phpstan-ignore-next-line  */
    $this->assertEquals($senderContact->getContactProfile()->email, $contact1Email);
});

test('that CommunicationDispatchProcesses are listable by the sender Contact, but Contact cannot see other Processes.', function () {

    /**
     * Creating a sender Contact
     */
    $contact1Email = 'test.contact.125@almail.com';
    $contact1 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => $contact1Email,
        'email_verified_at' => now(),
    ]);

    expect($contact1)->toBeObject();

    if (! $contact1 instanceof Contact) {
        $contact1 = null;
    }

    expect($contact1->getContactProfile())->toBeObject();
    expect($contact1->id)->toBeNumeric();

    /**
     * Creating a Communication process.
     * - creating a CommunicationCampaign
     * - creating a CommunicationDispatchProcess
     */
    $communicationCampaign = new CommunicationCampaign([
        'reference_code' => 'campaing_125',
        'title_lang_ref' => 'project.Campaing125',
        'is_published' => true,
        'is_listable' => true,
        'is_editable' => true,
        'is_redispatchable' => true,
    ]);
    $communicationCampaign->save();
    $communicationCampaign->refresh();
    expect($communicationCampaign)->toBeObject();
    expect($communicationCampaign->id)->toBeNumeric();

    $communicationDispatchProcess = new CommunicationDispatchProcess([
        'communicationcampaign_id' => $communicationCampaign->id,
        'sender_contact_id' => $contact1->id,
        'communication_form' => CommunicationForm::EMAIL->value,
        'status' => CommunicationDispatchProcessStatus::IN_PROGRESS->value,
    ]);
    $communicationDispatchProcess->save();
    $communicationDispatchProcess->refresh();
    expect($communicationDispatchProcess)->toBeObject();
    expect($communicationDispatchProcess->id)->toBeNumeric();

    /**
     * "Logging in"
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * Assertions.
     * - We are checking whether all the CommunicationDispatchProcesses on the list are sent by our Customer.
     */
    $list = CommunicationDispatchProcess::listableByCustomer()->get();
    $allListElementsSenderContactIsProper = true;
    foreach ($list as $listElement) {
        if ($listElement->senderContact()->first()->id != $contact1->id) {
            $allListElementsSenderContactIsProper = false;
        }
    }
    
    /** @phpstan-ignore-next-line  */
    $this->assertTrue($allListElementsSenderContactIsProper);
});
