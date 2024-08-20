<?php

declare(strict_types=1);

namespace Domain\Customer\DataTransferObjects;

use Domain\Customer\Models\OrgAddress;
use Domain\Shared\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\LaravelData\Data;

/**
 * Organization Shipping Address data object used in most product forms
 */
class OrganizationShippingAddressData extends Data
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
        if ($request->has('shipping_country_select')) {
            $countryParts = explode('|', strval($request->shipping_country_select));
            $country_code = $countryParts[1];
        } elseif ($request->has('country_select')) {
            $countryParts = explode('|', strval($request->country_select));
            $country_code = $countryParts[1];
        }
        $title = $request->form_name.' szállítási cím';
        $phone = (string) new PhoneNumber(strval($request->phone), strval($request->phone_country));

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
                'address_type' => $request->address_type ?? '',
            ];
        }
        if ($request->has('lane')) {
            $lane = $request->lane;
        } else {
            $lane = $lane_composed;
        }

        $postal_code = $request->postal_code;
        $city = $request->city;
        $region = $request->region ?? null;

        $notes = [
            'shipping_contact' => $request->lastname.' '.$request->firstname,
            'shipping_company' => $request->name,
            'shipping_email' => $request->email,
            'shipping_phone' => $phone,
        ];

        if (! $request->filled('shipping_is_same_as_billing')) {
            $postal_code = $request->shipping_postal_code;
            $city = $request->shipping_city;
            $lane = $request->shipping_lane;
            $region = $request->shipping_region ?? null;
            $phone = (string) new PhoneNumber(strval($request->shipping_phone), strval($request->shipping_phone_country));
            $notes = [
                'shipping_contact' => $request->shipping_contact ?? null,
                'shipping_company' => $request->shipping_company ?? null,
                'shipping_email' => $request->shipping_email ?? null,
                'shipping_phone' => $phone,
            ];
            $streetAddress = [];
            $taxAddressType = [];
        }

        $orgAddress = [
            'id' => $request->id ?? null,
            'organization_id' => $request->organization_id ?? null,
            'title' => $title,
            'type' => 'shipping',
            'main' => false,
            'postal_code' => $postal_code,
            'city' => $city,
            'lane' => $lane,
            'region' => $region,
            'country_code' => $country_code,
            'country_id' => Country::getIdByCode($country_code),
            'shipping_notes' => json_encode($notes),
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
        $country = $address->country_id ? Country::find($address->country_id) : null;

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
            'country_code' => $country ? $country->iso2 : null,
            'country_id' => $country ? $country->id : null,
            'shipping_notes' => $address->shipping_notes,
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
        return \Domain\Customer\Rules\ShippingAddressRules::rules();
    }
}
