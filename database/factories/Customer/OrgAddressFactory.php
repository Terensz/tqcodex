<?php

declare(strict_types=1);

namespace Database\Factories\Customer;

//use Domain\Customer\Enums\CorporateForm;

use Domain\Customer\Models\OrgAddress;
use Domain\Shared\Factories\Base\BaseFactory;
use Domain\Shared\Helpers\CountryHelper;

final class OrgAddressFactory extends BaseFactory
{
    protected $model = OrgAddress::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hungary = CountryHelper::getCountryObjectByCode('HU');

        return [
            'country_id' => $hungary->id,
            'main' => true,
            'postal_code' => fake()->postcode,
            'city' => fake()->city,
            'street_name' => fake()->streetName,
            'public_place_category' => 'Street', // vagy más érték, ha szükséges
            'number' => fake()->buildingNumber,
            'building' => fake()->numberBetween(1, 130),
            'floor' => fake()->randomDigitNotNull,
            'door' => fake()->randomDigitNotNull,
            'address' => fake()->address,
            'lane' => fake()->streetSuffix,
            'region' => 'HU',
            'shipping_notes' => fake()->sentence,
        ];
    }
}
