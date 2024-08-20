<?php

declare(strict_types=1);

namespace Domain\Customer\DataTransferObjects;

use Domain\Customer\Models\ContactProfileAddress;
use Domain\Shared\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Data;

/**
 * Contact data object used in most service forms
 */
class ContactAddressData extends Data
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
        if ($request->has('country_select')) {
            $countryParts = explode('|', strval($request->country_select));
            $country_code = $countryParts[1];
        }

        $title = $request->form_name.' számlacím';
        if ($request->has('title')) {
            $title = $request->title;
        }

        return self::from([
            'id' => $request->id ?? null,
            'contact_id' => $request->contact_id ?? null,
            'type' => 'billing',
            'title' => $title,
            'main' => $request->main ?? false,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'lane' => $request->lane,
            'region' => $request->region ?? null,
            'country_code' => $country_code,
            'country_id' => Country::getIdByCode($country_code),
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
        return \Domain\Customer\Rules\ContactAddressRules::rules();
    }
}
