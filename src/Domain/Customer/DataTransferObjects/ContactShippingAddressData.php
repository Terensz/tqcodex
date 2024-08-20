<?php

declare(strict_types=1);

namespace Domain\Customer\DataTransferObjects;

use Domain\Customer\Models\ContactProfileAddress;
use Domain\Shared\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\LaravelData\Data;

/**
 * Contact Shipping Address data object used in most product sales forms
 */
class ContactShippingAddressData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $contact_id,
        public readonly string $title,
        public readonly string $type,
        public readonly bool $main,
        public readonly string $postal_code,
        public readonly string $city,
        public readonly string $lane,
        public readonly ?string $region,
        public readonly ?string $country_code,
        public readonly ?int $country_id,
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

        $postal_code = $request->postal_code;
        $city = $request->city;
        $lane = $request->lane;
        $region = $request->region ?? null;

        $title = $request->form_name.' szállítási cím';
        $phone = (string) new PhoneNumber(strval($request->phone), strval($request->phone_country));

        $notes = [
            'shipping_contact' => $request->lastname.' '.$request->firstname,
            'shipping_company' => null,
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
        }

        return self::from([
            'id' => $request->id ?? null,
            'contact_id' => $request->contact_id ?? null,
            'type' => 'shipping',
            'title' => $title,
            'main' => false,
            'postal_code' => $postal_code,
            'city' => $city,
            'lane' => $lane,
            'region' => $region ?? null,
            'country_code' => $country_code,
            'country_id' => Country::getIdByCode($country_code),
            'shipping_notes' => json_encode($notes),
        ]);
    }

    public static function fromModel(ContactProfileAddress $address): self
    {
        return self::from([
            'id' => $address->id,
            'contact_profile_id' => $address->contact_profile_id,
            'type' => $address->type,
            'title' => $address->title,
            'main' => $address->main,
            'postal_code' => $address->postal_code,
            'city' => $address->city,
            'lane' => $address->lane,
            'region' => $address->region,
            'country_code' => $address->country_code,
            'country_id' => $address->country_id,
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
