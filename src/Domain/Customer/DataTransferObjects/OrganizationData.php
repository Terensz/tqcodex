<?php

declare(strict_types=1);

namespace Domain\Customer\DataTransferObjects;

use Domain\Customer\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\LaravelData\Data;

/**
 * Organization data object used in most service forms
 */
class OrganizationData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $long_name,
        public readonly ?string $taxpayer_id,
        public readonly ?string $vat_code,
        public readonly ?string $county_code,
        public readonly ?string $vat_verified_at,
        public readonly ?string $org_type,
        // public readonly ?string $address_type,
        public readonly ?string $company_email,
        public readonly ?string $company_phone,
        public readonly ?string $country_code,
        public readonly ?string $eutaxid,
        public readonly ?string $taxid,
        public readonly ?string $location,
        public readonly ?string $social_media,
        public readonly ?string $map_coordinates,
        public readonly ?string $description,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $phone = (string) new PhoneNumber(strval($request->phone), strval($request->phone_country));

        $orgData = [
            'id' => $request->id ?? null,
            'name' => $request->name,
            'company_phone' => $phone,
            'company_email' => $request->email,
        ];

        if ($request->has('billing_to')) {
            switch ($request->billing_to) {
                case 'huco':
                    $orgData = [...$orgData, ...[
                        'long_name' => $request->long_name,
                        'taxpayer_id' => $request->taxpayer_id,
                        'vat_code' => $request->vat_code,
                        'county_code' => $request->county_code,
                        'vat_verified_at' => $request->vat_verified_at,
                        'org_type' => $request->org_type,
                        // 'address_type' => $request->address_type,
                        'location' => 'HU',
                        'country_code' => 'HU',
                    ],
                    ];
                    break;

                case 'euco':
                    $orgData = [...$orgData, ...[
                        'eutaxid' => $request->eutaxid,
                        'country_code' => $request->country_code,
                        'location' => 'EU',
                    ],
                    ];
                    break;

                default:
                    //Any other non HU and non EU company
                    $countryParts = explode('|', strval($request->country_select));

                    $orgData = [...$orgData, ...[
                        'taxid' => $request->taxid,
                        'country_code' => $countryParts[1],
                        'location' => 'EUK',
                    ],
                    ];
            }
        }

        return self::from($orgData);
    }

    public static function fromModel(Organization $org): self
    {
        return self::from([
            'id' => $org->id,
            'name' => $org->name,
            'email' => $org->email,
            'phone' => $org->phone,
            'taxpayer_id' => $org->taxpayer_id,
            'eutaxid' => $org->eutaxid,
            'taxid' => $org->taxid,
            'country_code' => $org->country_code,
            'location' => $org->location,
            'description' => $org->description,
        ]);
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->setRules(self::rules());
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return \Domain\Customer\Rules\OrganizationRules::rules();
    }
}
