<?php

declare(strict_types=1);

namespace Domain\Customer\Rules;

use Domain\Shared\Enums\StreetSuffix;
use Domain\Shared\Enums\TrueOrFalse;
use Domain\Shared\Rules\ValidEnum;
use Illuminate\Validation\Rule;

class OrgAddressRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'organization_id' => [
                'required',
                new OrganizationMatchesToContact,
            ],
            'country_id' => [
                'required',
                new ExistingCountryId,
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'main' => [
                'required',
                Rule::in(TrueOrFalse::getPossibleValidationValues()),
            ],
            'address_type' => [
                'nullable', // Maradjon nullable!
            ],
            'postal_code' => [
                'required',
                'numeric',
                'digits:4',
            ],
            'region' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'required',
                'string',
            ],
            'street_name' => [
                'required',
                'string',
                'max:255',
            ],
            'public_place_category' => [
                'required',
                new ValidEnum(StreetSuffix::class),
            ],
            'number' => [
                'required',
                'string',
                'max:30',
            ],
            'building' => [
                'nullable',
                'string',
                'max:30',
            ],
            'floor' => [
                'nullable',
                // 'sometimes',
                'string',
                'max:30',
            ],
            'door' => [
                'nullable',
                // 'sometimes',
                'string',
                'max:30',
            ],
            // 'postaddress' => ['nullable', 'sometimes', 'string', 'max:255'],
            'lane' => [
                'nullable',
                'string',
                'max:512',
            ],
        ];
    }
}
