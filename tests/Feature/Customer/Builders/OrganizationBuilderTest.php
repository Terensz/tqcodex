<?php

use Database\Factories\Customer\ContactFactory;
use Database\Factories\Customer\OrganizationFactory;
use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactOrg;
use Domain\Customer\Models\ContactProfileOrganization;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Finance\Models\PartnerOrg;
use Domain\Shared\Models\Country;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/Customer/Builders/OrganizationBuilderTest.php
*/

uses()->group('builder');

test('that Organization builder finds valid Organizations', function () {

    $validOrganizations = ContactOrg::valid()->get();

    expect($validOrganizations)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});

test('that Organization which has ContactProfile is listable by hasContactProfiles() method', function () {

    $contact = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => 'test.contact.125@almail.com',
        'email_verified_at' => now(),
    ]);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    expect($contact)->toBeObject();

    if (! $contact instanceof Contact) {
        $contact = null;
    }

    $org = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 2000 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 2000 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);

    expect($org)->toBeObject();

    if (! $org instanceof Organization) {
        $org = null;
    }

    OrgAddress::create([
        'organization_id' => $org->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);

    $contactProfileOrganization = ContactProfileOrganization::create([
        'contact_profile_id' => $contact->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);

    expect($contactProfileOrganization)->toBeObject();

    $currentOrgsBoundsContactProfile = ContactOrg::hasContactProfiles()->find($org->id);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals($org->id, $currentOrgsBoundsContactProfile->id);
});

/**
 * - Creating a Contact
 * - Creating an Org
 * - Connecting the Contact with the Org
 * - Acting as this Contact
 * - Listing orgs with the OrganizationBuilder's listableByCustomer() method
 * - Checking if own Org is on the list.
 */
test('that a Contact bound to an Organization can search this Organization', function () {

    $contact = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => 'test.contact.125@almail.com',
        'email_verified_at' => now(),
    ]);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    expect($contact)->toBeObject();

    if (! $contact instanceof Contact) {
        $contact = null;
    }

    $org = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 2000 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 2000 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);

    expect($org)->toBeObject();

    if (! $org instanceof Organization) {
        $org = null;
    }

    OrgAddress::create([
        'organization_id' => $org->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);

    $contactProfileOrganization = ContactProfileOrganization::create([
        'contact_profile_id' => $contact->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);

    expect($contactProfileOrganization)->toBeObject();

    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $listableOrgs = ContactOrg::listableByCustomer()->get();
    $ownOrgFound = false;
    foreach ($listableOrgs as $listableOrg) {
        if ($listableOrg->id == $org->id) {
            $ownOrgFound = true;
        }
    }

    /** @phpstan-ignore-next-line  */
    $this->assertTrue($ownOrgFound);
});

/**
 * - Creating a Contact
 * - Creating an Org
 * - Creating another Contact
 * - Connecting the ANOTHER Contact with the Org
 * - Acting as the first Contact
 * - Listing orgs with the OrganizationBuilder's listableByCustomer() method
 * - Checking if own Org is NOT on the list, since it belongs to an ANOTHER Contact
 */
test('that a Contact NOT bound to an Organization CANNOT search this Organization', function () {

    $contact1 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => 'test.contact.125@almail.com',
        'email_verified_at' => now(),
    ]);

    if (! $contact1 instanceof Contact) {
        $contact1 = null;
    }

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    expect($contact1)->toBeObject();

    $org = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 2000 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 2000 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);

    expect($org)->toBeObject();

    if (! $org instanceof Organization) {
        $org = null;
    }

    OrgAddress::create([
        'organization_id' => $org->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);

    /**
     * Creating another Contact
     */
    $contact2 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test126',
        'lastname' => 'Contact',
        'email' => 'test.contact.126@almail.com',
        'email_verified_at' => now(),
    ]);

    if (! $contact2 instanceof Contact) {
        $contact2 = null;
    }

    /**
     * Binding the Org to the ANOTHER Contact
     */
    $contactProfileOrganization = ContactProfileOrganization::create([
        'contact_profile_id' => $contact2->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);

    expect($contactProfileOrganization)->toBeObject();

    /**
     * Acting as the FIRST Contact
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $listableOrgs = ContactOrg::listableByCustomer()->get();
    $ownOrgFound = false;
    /**
     * Org should not be on the list, since it belongs to an ANOTHER Contact
     */
    foreach ($listableOrgs as $listableOrg) {
        if ($listableOrg->id == $org->id) {
            $ownOrgFound = true;
        }
    }

    /** @phpstan-ignore-next-line  */
    $this->assertNotTrue($ownOrgFound);
});

/**
 * Now we are testing three different Contacts' access to an Org, with the OrganizationBuilder's listableByCustomer() method.
 * The first TWO belongs to that Org, the third is NOT.
 * - We are creating the 3 Contacts.
 * - We are creating the Org.
 * - Associating the first TWO Contacts to the Org.
 * - Acting as the FIRST Contact we check if we CAN see the Org among the listableByCustomer() results.
 * - Acting as the SECOND Contact we check if we CAN see the Org among the listableByCustomer() results.
 * - Acting as the THIRD Contact we check if we CANNOT see the Org among the listableByCustomer() results, since it does not belong to us.
 */
test('that two Contacts bound to the SAME Organization, and a third is not. Two associated Contacts must be able to search this Org, the third is NOT.', function () {

    $contact1 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => 'test.contact.125@almail.com',
        'email_verified_at' => now(),
    ]);
    $contact2 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test126',
        'lastname' => 'Contact',
        'email' => 'test.contact.126@almail.com',
        'email_verified_at' => now(),
    ]);
    $contact3 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test127',
        'lastname' => 'Contact',
        'email' => 'test.contact.127@almail.com',
        'email_verified_at' => now(),
    ]);

    // AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    expect($contact1)->toBeObject();
    expect($contact2)->toBeObject();
    expect($contact3)->toBeObject();

    if (! $contact1 instanceof Contact) {
        $contact1 = null;
    }
    if (! $contact2 instanceof Contact) {
        $contact2 = null;
    }
    if (! $contact3 instanceof Contact) {
        $contact3 = null;
    }

    $org = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 2000 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 2000 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);

    expect($org)->toBeObject();

    if (! $org instanceof Organization) {
        $org = null;
    }

    OrgAddress::create([
        'organization_id' => $org->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);

    /**
     * Binding the Org to 2 contacts of the 3
     */
    $contactProfileOrganization1 = ContactProfileOrganization::create([
        'contact_profile_id' => $contact1->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);
    $contactProfileOrganization2 = ContactProfileOrganization::create([
        'contact_profile_id' => $contact2->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);

    expect($contactProfileOrganization1)->toBeObject();
    expect($contactProfileOrganization2)->toBeObject();

    /**
     * Acting as the FIRST Contact
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $currentOrgsBoundsContactProfile1 = ContactOrg::listableByCustomer()->find($org->id);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals($currentOrgsBoundsContactProfile1->id, $org->id);

    /**
     * Acting as the SECOND Contact
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact2, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $currentOrgsBoundsContactProfile2 = ContactOrg::listableByCustomer()->find($org->id);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals($currentOrgsBoundsContactProfile2->id, $org->id);

    /**
     * Acting as the THIRD Contact
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact3, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $currentOrgsBoundsContactProfile3 = ContactOrg::listableByCustomer()->find($org->id);

    /** @phpstan-ignore-next-line  */
    $this->assertNull($currentOrgsBoundsContactProfile3);
});

/**
 * - Creating a Contact.
 * - Creating an Org.
 * - Asserting if acting as the Contact the NOT associated Org is ON the potentialPartner() list.
 * - Associating the Org with the Contact
 * - Asserting if acting as the Contact the ALREADY associated Org is NOT on the potentialPartner() list anymore.
 */
test('that potentialPartner() method lists only Orgs which are not bound to the current Contact.', function () {

    $contact1 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test125',
        'lastname' => 'Contact',
        'email' => 'test.contact.125@almail.com',
        'email_verified_at' => now(),
    ]);
    $contact2 = ContactFactory::new()->createUntilNotTaken([
        'firstname' => 'Test126',
        'lastname' => 'Contact',
        'email' => 'test.contact.126@almail.com',
        'email_verified_at' => now(),
    ]);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    expect($contact1)->toBeObject();
    expect($contact2)->toBeObject();

    if (! $contact1 instanceof Contact) {
        $contact1 = null;
    }
    if (! $contact2 instanceof Contact) {
        $contact2 = null;
    }

    $org = OrganizationFactory::new()->createUntilNotTaken([
        'name' => 'Random 2000 Kft.',
        'org_type' => CorporateForm::LTD->value,
        'long_name' => 'Random 2000 Kft.',
        'taxpayer_id' => '10000125-1-41',
        'vat_code' => 1,
        'county_code' => 41,
        'country_code' => 'HU',
    ]);

    expect($org)->toBeObject();

    if (! $org instanceof Organization) {
        $org = null;
    }

    OrgAddress::create([
        'organization_id' => $org->id,
        'title' => 'Random 2000 Kft. székhelye',
        'main' => true,
        'country_id' => Country::getIdByCode('HU'),
        'postal_code' => '5008',
        'city' => 'Szolnok',
        'street_name' => 'Petőfi Sándor',
        'public_place_category' => 'utca',
        'number' => '125',
    ]);

    /**
     * Binding the Org to the 2nd Contact
     */
    $contactProfileOrganization2 = ContactProfileOrganization::create([
        'contact_profile_id' => $contact2->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);
    expect($contactProfileOrganization2)->toBeObject();

    /**
     * Acting as the Contact.
     */
    /** @phpstan-ignore-next-line  */
    $this->actingAs($contact1, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * Asserting that the Org is ON the potentialPartner() list.
     */
    $orgOnPotentialPartnerList1 = PartnerOrg::potentialPartner()->find($org->id);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals($orgOnPotentialPartnerList1->id, $org->id);

    /**
     * Binding the Org to the Contact
     */
    $contactProfileOrganization1 = ContactProfileOrganization::create([
        'contact_profile_id' => $contact1->getContactProfile()->id,
        'organization_id' => $org->id,
    ]);
    expect($contactProfileOrganization1)->toBeObject();

    /**
     * Acting as the Contact, and asserting that the Org is NOT on the potentialPartner() list, since we already accociated them.
     */
    $orgOnPotentialPartnerList1 = PartnerOrg::potentialPartner()->find($org->id);

    /** @phpstan-ignore-next-line  */
    $this->assertNull($orgOnPotentialPartnerList1);

});
