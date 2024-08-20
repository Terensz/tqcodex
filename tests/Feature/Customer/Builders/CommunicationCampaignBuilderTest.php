<?php

use Database\Factories\Customer\ContactFactory;
use Database\Factories\Customer\OrganizationFactory;
use Domain\Communication\Models\CommunicationCampaign;
use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Customer\Services\ContactProfileOrganizationSeederService;
use Domain\Shared\Models\Country;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Customer/Builders/CommunicationCampaignBuilderTest.php
*/

uses()->group('builder');

test('that CommunicationCampaign builder finds valid CommunicationCampaign.', function () {

    $validCommunicationDispatches = CommunicationCampaign::valid()->get();

    expect($validCommunicationDispatches)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});

test('that CommunicationCampaign which has Organization is listable by hasOrganizationListableByCustomer() method.', function () {

    /**
     * Creating a Contact
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
     * Creating an Organization for the sender Contact
     */
    $org1 = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 125 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 125 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);
    expect($org1)->toBeObject();

    if (! $org1 instanceof Organization) {
        $org1 = null;
    }

    OrgAddress::create([
        'organization_id' => $org1->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);
    $org1->refresh();

    $contactProfileOrganizationSeederService = new ContactProfileOrganizationSeederService;
    $contactProfileOrganizationSeederService->createContactProfileOrganization($org1->id, $contact1->getContactProfile());

    /**
     * Creating a CommunicationCampaign
     */
    $communicationCampaign = new CommunicationCampaign([
        'organization_id' => $org1->id,
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

    /**
     * "Logging in"
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * Do the assertings.
     * - Searching a CommunicationCampaign where the current Contact is the sender.
     * - Asserting if the found CommunicationDispatchProcess has the sender equal the current Contact.
     */
    $foundObject = CommunicationCampaign::hasOrganizationListableByCustomer()->where(['organization_id' => $org1->id])->first();
    expect($foundObject)->toBeObject();
    /** @phpstan-ignore-next-line  */
    $this->assertEquals($foundObject->id, $communicationCampaign->id);
});

test('that CommunicationCampaign is only listable if no Organization associated OR current Customer is member of the Organization.', function () {

    /**
     * Now lets start with creating an Organization.
     */
    $org1 = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 125 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 125 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);
    expect($org1)->toBeObject();

    if (! $org1 instanceof Organization) {
        $org1 = null;
    }

    OrgAddress::create([
        'organization_id' => $org1->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);
    $org1->refresh();

    /**
     * Creating a Contact, he will be a member of the Organization.
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
     * Now lets associate him with our Org.
     */
    $contactProfileOrganizationSeederService = new ContactProfileOrganizationSeederService;
    $contactProfileOrganizationSeederService->createContactProfileOrganization($org1->id, $contact1->getContactProfile());

    /**
     * Creating another Contact, he will NOT be an Org member.
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
    expect($contact2->id)->toBeNumeric();

    /**
     * Now lets create a CommunicationCampaign, this will be associated with our Org.
     */
    $communicationCampaign = new CommunicationCampaign([
        'organization_id' => $org1->id,
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

    /**
     * Now lets create ANOTHER CommunicationCampaign, this will NOT be associated with ANY Orgs.
     */
    $communicationCampaign = new CommunicationCampaign([
        'organization_id' => null,
        'reference_code' => 'campaing_126',
        'title_lang_ref' => 'project.Campaing126',
        'is_published' => true,
        'is_listable' => true,
        'is_editable' => true,
        'is_redispatchable' => true,
    ]);
    $communicationCampaign->save();
    $communicationCampaign->refresh();
    expect($communicationCampaign)->toBeObject();
    expect($communicationCampaign->id)->toBeNumeric();

    /**
     * Lets do the assertions.
     * At first let's log in as the Contact1. He should see both CommunicationCampaigns, since the first one is associated to his Org, and the second one is visible for anyone.
     */

    /**
     * "Logging in"
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * This Contact must be able to list all CommunicationCampaign with NULL organization_id, OR organization_ids bound to him.
     * In this example he will get one with ID, and a lot of others (because of the seeders) with NULL organization_id.
     */
    $result = CommunicationCampaign::listableByCustomer()
        // ->where(['id' => $communicationCampaign->id])
        ->get();

    $containsOrgIdNull = false;
    $orgIds = [];
    foreach ($result as $resultRow) {
        if (! $resultRow->organization_id) {
            $containsOrgIdNull = true;
        } else {
            $orgIds[] = $resultRow->organization_id;
        }
    }

    /** @phpstan-ignore-next-line  */
    $this->assertEquals(true, $containsOrgIdNull);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals(1, count($orgIds));

    /**
     * "Logging in" as Contact2.
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact2, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * This Contact must be able to list all CommunicationCampaign with NULL organization_id, since no Organizations are bound to him.
     */
    $result = CommunicationCampaign::listableByCustomer()
        // ->where(['id' => $communicationCampaign->id])
        ->get();

    $containsOrgIdNull = false;
    $orgIds = [];
    foreach ($result as $resultRow) {
        if (! $resultRow->organization_id) {
            $containsOrgIdNull = true;
        } else {
            $orgIds[] = $resultRow->organization_id;
        }
    }

    /** @phpstan-ignore-next-line  */
    $this->assertEquals(true, $containsOrgIdNull);

    /**
     * No Org ids are bound to him.
     */
    /** @phpstan-ignore-next-line  */
    $this->assertEquals(0, count($orgIds));
});
