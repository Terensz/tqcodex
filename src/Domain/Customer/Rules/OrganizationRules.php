<?php

declare(strict_types=1);

namespace Domain\Customer\Rules;

use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Enums\Location;
use Domain\Customer\Services\OrganizationService;
use Domain\Finance\Enums\CountyCode;
use Domain\Finance\Rules\ValidEuTaxId;
use Domain\Finance\Rules\ValidHungarianTaxNumber;
use Domain\Finance\Rules\ValidHungarianTaxNumberFormat;
use Domain\Shared\Enums\TrueOrFalse;
use Domain\Shared\Rules\ValidEnum;
use Illuminate\Validation\Rule;

class OrganizationRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            // 'billing_to' => ['required', Rule::in(['priv', 'huco', 'euco', 'otco'])],
            // 'type' => ['required', Rule::in(['b2b', 'b2c'])],
            // 'taxpayer_id' => ['required_if:billing_to,huco', 'string', 'max:15'],
            'is_banned' => [
                'required',
                Rule::in(TrueOrFalse::getPossibleValidationValues()),
            ],
            'name' => [
                'required',
                'string',
                'max:'.OrganizationService::NAME_MAX_DIGITS,
            ],
            'long_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            // adószám
            'taxpayer_id' => [
                'required_if:location,'.Location::HU->value,
                // new ValidHungarianTaxNumberFormat(),
                new ValidHungarianTaxNumber(OrganizationRules::class, 'taxpayer_id'),
            ],
            // ÁFA-kód
            'vat_code' => [
                'nullable',
                'numeric',
                'between:1,5',
            ],
            // Országkód
            'country_code' => [
                'nullable',
                'string',
                'max:3',
            ],
            // megyekód
            'county_code' => [
                'nullable',
                'string',
                new ValidEnum(CountyCode::class),
            ],
            'vat_verified_at' => [
                'nullable',
                'date',
            ],
            'vat_banned' => [
                'required',
                Rule::in(TrueOrFalse::getPossibleValidationValues()),
            ],
            'org_type' => [
                'required',
                new ValidEnum(CorporateForm::class),
            ],
            'taxid' => [
                'nullable',
                'sometimes',
                'string',
                'max:50',
            ],
            'eutaxid' => [
                'required_if:location,'.Location::EU->value,
                new ValidEuTaxId,
            ],
            'location' => [
                'required',
                'string',
                'max:4',
                new ValidEnum(Location::class),
            ],
            'phone' => [
                'nullable',
                'phone:INTERNATIONAL,HU',
            ],
            // 'phone_country' => ['required_with:phone'],
            'email' => [
                'nullable',
                'email:rfc,dns',
            ],
        ];
    }
}
