<?php

namespace Tests\Feature\Customer\Project;

use Domain\Project\Rules\ContactRegisterRules;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

/*
Standalone run:
php artisan test tests/Feature/Customer/Registration/CustomerRegisterRuleTest.php
*/

uses()->group('project');

beforeEach(function () {

    App::setLocale('hu');

    /** @phpstan-ignore-next-line  */
    $this->rules = ContactRegisterRules::rules();
});

it('can detect valid fields on the Contact register interface', function ($setData): void {

    /** @phpstan-ignore-next-line  */
    $validator = Validator::make($setData, $this->rules);
    if (! $validator->passes()) {
        dump($validator->messages());
    }

    /** @phpstan-ignore-next-line  */
    $this->assertTrue($validator->passes());
    expect(true)->toBeTrue();
})->with('ContactRegisterDataOK');

it('can detect missing or wrong fields on the Contact register interface', function ($setData): void {

    /** @phpstan-ignore-next-line  */
    $validator = Validator::make($setData, $this->rules);
    // if (! $validator->passes()) {
    //     dump($validator->messages());
    // }
    
    /** @phpstan-ignore-next-line  */
    $this->assertFalse($validator->passes());
    expect(true)->toBeTrue();
})->with('ContactRegisterDataWrong');

/*
document.getElementById('input_lastname').value = 'Papp';
document.getElementById('input_firstname').value = 'Ferenc';
document.getElementById('input_email').value = 'terence.cleric@gmail.com';
document.getElementById('input_mobile').value = '+36704445555';
document.getElementById('input_organization_name').value = 'Kovács és Anyósa Rt.';
document.getElementById('input_organization_org_type').value = 'OpenIncorporated';
document.getElementById('input_organization_email').value = 'info@kovacsestarsa.hu';
document.getElementById('input_organization_taxpayer_id').value = '12345630-1-41';
document.getElementById('input_organization_vat_banned').value = '1';
*/
