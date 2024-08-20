<?php

declare(strict_types=1);

namespace Domain\Customer\DataTransferObjects;

use Domain\Customer\Models\OrgAddress;
use Domain\Shared\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Data;

/**
 * Contact data object used in most service forms
 */
class OrganizationAddressData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $organization_id,
        public readonly string $title,
        public readonly string $type,
        public readonly bool $main,
        public readonly string $postal_code,
        public readonly string $city,
        public readonly string $lane,
        public readonly ?string $region,
        public readonly ?string $country_code,
        public readonly ?int $country_id,
        public readonly ?string $address_type,
        public readonly ?string $street_name,
        public readonly ?string $public_place_category,
        public readonly ?string $number,
        public readonly ?string $building,
        public readonly ?string $floor,
        public readonly ?string $door,
        public readonly ?string $shipping_notes,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $country_code = 'HU';
        if ($request->has('country_select')) {
            $countryParts = explode('|', strval($request->country_select));
            $country_code = $countryParts[1];
        }
        $title = $request->form_name.' számlacím';
        if ($request->has('title')) {
            $title = $request->title;
        }
        $lane_composed = '';
        $streetAddress = [];
        $taxAddressType = [];
        if ($request->has('street_name')) {
            $streetAddress = [
                'street_name' => $request->street_name ?? '',
                'public_place_category' => $request->public_place_category ?? '',
                'number' => $request->number ?? '',
                'building' => $request->building ?? '',
                'floor' => $request->floor ?? '',
                'door' => $request->door ?? '',
            ];
            $lane_composed = implode(' ', array_filter($streetAddress));
            $taxAddressType = [
                'type' => $request->type ?? '',
            ];
        }
        $lane = $lane_composed;
        if ($request->has('lane')) {
            $lane = $request->lane;
        }

        $orgAddress = [
            'id' => $request->id ?? null,
            'organization_id' => $request->organization_id ?? null,
            'title' => $title,
            'type' => 'billing',
            'main' => $request->main ?? false,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'lane' => $lane,
            'region' => $request->region ?? null,
            'country_code' => $country_code,
            'country_id' => Country::getIdByCode($country_code),
        ];
        $orgAddress = [
            ...$orgAddress,
            ...$taxAddressType,
            ...$streetAddress,
        ];

        return self::from($orgAddress);
    }

    public static function fromModel(OrgAddress $address): self
    {
        return self::from([
            'id' => $address->id,
            'organization_id' => $address->organization_id,
            'type' => $address->type,
            // 'address_type' => $address->address_type, //HQ or other
            'title' => $address->title,
            'main' => $address->main,
            'postal_code' => $address->postal_code,
            'city' => $address->city,
            'lane' => $address->getLane(),
            'region' => $address->region,
            'country_code' => $address->country()->first()->iso2,
            'country_id' => $address->country_id,
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
        return \Domain\Customer\Rules\OrgAddressRules::rules();
    }
}
