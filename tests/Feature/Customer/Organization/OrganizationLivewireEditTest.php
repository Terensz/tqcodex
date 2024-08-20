<?php

use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Customer\Services\OrganizationSeederService;
use Domain\Shared\Enums\StreetSuffix;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;

/*
Standalone run:
php artisan test tests/Feature/Customer/Organization/OrganizationLivewireEditTest.php
*/

test('Quick Livewire test - OrganizationEdit', function () {

    App::setLocale('hu');

    $contactProfile = ContactProfile::where(['email' => 'terencecleric@gmail.com'])->first();
    $user = $contactProfile->getContact();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $calisto = OrganizationSeederService::getNthOrgOfContact(2, 0);

    /**
     * In the first round we set only correct new values.
     */
    $test = Livewire::test(\Domain\Customer\Livewire\OrganizationEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'organization' => $calisto,
    ])
        ->assertSet('form.is_banned', false)
        ->assertSet('form.name', $calisto->name)
        // Changing values
        ->set('form.name', 'CBA-Grand Gourmet Kft.')
        ->set('form.taxpayer_id', '25072394-2-42')
        ->set('mainAddressForm.public_place_category', StreetSuffix::ALLEY->value)
        // Submitting
        ->call('save');

    $component = $test->instance();

    /**
     * Asserting that form has no errors.
     */
    /** @phpstan-ignore-next-line  */
    $this->assertEquals([], $component->getErrorBag()->getMessages());

    $calisto->refresh();

    /**
     * Now asserting that the name we changed on the form was really changed.
     */
    /** @phpstan-ignore-next-line  */
    $this->assertEquals('CBA-Grand Gourmet Kft.', $calisto->name);

    /**
     * In the first round we set only correct new values.
     */
    $test = Livewire::test(\Domain\Customer\Livewire\OrganizationEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'organization' => $calisto,
    ])
        // Changing values
        ->set('form.name', 'TRIEX KFT')
        ->set('form.taxpayer_id', '10352281-2-43')
        ->call('save');

    $component = $test->instance();

    $calisto->refresh();

    // dump($calisto->name);

    /**
     * Now asserting that the public_place_category we changed on the form was really changed.
     * But this data belongs to the OrgAddress model.
     */
    $calistoAddress = OrgAddress::where(['organization_id' => $calisto->id])->first();

    /** @phpstan-ignore-next-line  */
    $this->assertEquals(StreetSuffix::ALLEY->value, $calistoAddress->public_place_category);
});
