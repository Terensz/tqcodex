<?php

namespace Tests\Feature\Validation;

use Domain\Customer\Actions\TaxpayerId;
use Domain\Finance\Rules\EitherPartnerOrgIdOrTaxpayerId;
use Illuminate\Support\Facades\Validator;

/*
Standalone run:
php artisan test tests/Feature/Validation/ValidationRulesForPartnerdataTest.php
*/

// uses()->group('spec');

// beforeEach(function () {
//     $this->rules = [
//         'partner_org_id' => [
//             'exclude_if:valid_taxpayer_id,true',
//             'required_without:partner_taxpayer_id',
//             'integer',
//             'numeric',
//             'gt:0',
//         ],
//         'partner_taxpayer_id' => [
//             'exclude_if:valid_partner_org_id,true',
//             'required_without:partner_org_id',
//             'string',
//             'regex:/^\d{8}(-\d{1}-\d{2})?$/i',
//             'max:13',
//         ],
//     ];

//     $this->otherRules = [
//         'partner_org_id' => ['sometimes', 'nullable', 'integer', 'numeric', 'gt:0', new EitherPartnerOrgIdOrTaxpayerId()],
//         'partner_taxpayer_id' => ['sometimes', 'nullable', 'string', 'max:13', new EitherPartnerOrgIdOrTaxpayerId()],
//     ];
// });

// it('can detect valid fields in compensationItems requests', function ($partnerids): void {

//     $okTaxId = TaxpayerId::check($partnerids['partner_taxpayer_id'] ?? '');
//     $valid_partner_org_id = false;
//     if ($partnerids['partner_org_id'] ?? false) {
//         $valid_partner_org_id = true;
//     }
//     $partnerids = [
//         ...$partnerids,
//         'valid_taxpayer_id' => $okTaxId,
//         'valid_partner_org_id' => $valid_partner_org_id,
//     ];
//     $validator = Validator::make($partnerids, $this->rules);

//     $this->assertTrue($validator->passes());

//     //echo print_r($validator->errors()->keys(), true);
//     expect(true)->toBeTrue();
// })->with('CompensationItemPartnerdataOK')->skip();

// it('can detect missing or wrong fields in compensationItems requests', function ($wrongpartnerdata): void {
//     $ok = TaxpayerId::check($wrongpartnerdata['partner_taxpayer_id'] ?? '');
//     $valid_partner_org_id = false;
//     if ($partnerids['partner_org_id'] ?? false) {
//         $valid_partner_org_id = true;
//     }
//     $wrongpartnerdata = [
//         ...$wrongpartnerdata,
//         'valid_taxpayer_id' => $ok,
//         'valid_partner_org_id' => $valid_partner_org_id,
//     ];
//     $validator = Validator::make($wrongpartnerdata, $this->rules);

//     $this->assertFalse($validator->passes());

//     //echo print_r($validator->errors()->keys(), true);
//     expect(true)->toBe(true);
// })->with('CompensationItemPartnerdataWrong')->skip();
