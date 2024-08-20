<?php

namespace Tests\Feature\Customer\Project;

use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Rules\OrganizationRules;
use Domain\Shared\Helpers\ArrayHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

/*
Standalone run:
php artisan test tests/Feature/Customer/Organization/OrganizationValidationRulesTest.php
*/

uses()->group('spec');

beforeEach(function () {
    App::setLocale('hu');

    $contactProfile = ContactProfile::where(['email' => 'terencecleric@gmail.com'])->first();
    $user = $contactProfile->getContact();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    /** @phpstan-ignore-next-line  */
    $this->rules = OrganizationRules::rules();

    /** @phpstan-ignore-next-line  */
    $this->currentRules = ArrayHelper::extractByKeys([
        'is_banned',
        'name',
        'long_name',
        'taxpayer_id',
        'vat_code',
        'county_code',
        'vat_verified_at',
        'vat_banned',
        'org_type',
        'country_code',
        'taxid',
        'eutaxid',
        'location',
        'phone',
        'email',
    /** @phpstan-ignore-next-line  */
    ], $this->rules);
});

it('can detect valid fields in organizations requests', function ($setData): void {
    // dump(UserService::getContact());exit;

    /** @phpstan-ignore-next-line  */
    $validator = Validator::make($setData, $this->currentRules);
    // if (! $validator->passes()) {
    //     dump($validator->messages());
    // }
    /** @phpstan-ignore-next-line  */
    $this->assertTrue($validator->passes());
    expect(true)->toBeTrue();
})->with('OrganizationDataOK');

it('can detect missing or wrong fields in organizations requests', function ($setData): void {
    /** @phpstan-ignore-next-line  */
    $validator = Validator::make($setData, $this->currentRules);

    /** @phpstan-ignore-next-line  */
    $this->assertFalse($validator->passes());
    expect(true)->toBeTrue();
})->with('OrganizationDataWrong');
