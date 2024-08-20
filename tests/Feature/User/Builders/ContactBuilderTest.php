<?php

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/User/Builders/ContactBuilderTest.php
*/

uses()->group('builder');

test(' if customers can only list their own Contact entity using Contact::listableByCustomer().', function () {

    $contact1 = Contact::whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, 'terencecleric@gmail.com')->first();
    $contact1email = $contact1->getContactProfile()->email;
    $this->assertIsString($contact1email);

    $contact2 = Contact::factory()->createUntilNotTaken();
    $contact2email = $contact2->getContactProfile()->email;
    $this->assertIsString($contact2email);

    $this->assertNotEquals($contact1email, $contact2email);

    $this->actingAs($contact2, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /**
     * Now $contact2 tries fo filter himself as $contact1. Must fail!
     */
    $result1 = Contact::listableByCustomer()->whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, $contact1email)->get();

    $this->assertEquals(0, count($result1));

    /**
     * Now $contact2 tries fo filter himself as $contact2. Must succeed!
     */
    $result2 = Contact::listableByCustomer()->whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, $contact2email)->get();

    $this->assertEquals(1, count($result2));
});

test('find terence contact by email address', function () {

    $contact = Contact::whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, 'terencecleric@gmail.com')->first();

    $this->assertEquals($contact->getContactProfile()->firstname, 'Ferenc');
});

test('that contact builder works with profile', function () {

    // Get all users with valid profiles
    $validContacts = Contact::valid()->get();

    expect($validContacts)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});

test('that contact profile relations is on', function () {

    // Get a specific Conatact and their profile
    $contact = Contact::find(1);
    $contactProfile = $contact->contactProfile;
    expect($contactProfile)->toBeInstanceOf(ContactProfile::class);

    // Other name:
    $contactProfileLonger = $contact->contactProfile;
    expect($contactProfileLonger)->toBeInstanceOf(ContactProfile::class);

    expect(true)->toBeTrue();
});

test('that contactprofiles builder works', function () {

    // Get all completed profiles directly
    $validProfiles = ContactProfile::valid()->get();
    expect($validProfiles)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});
