<?php

declare(strict_types=1);

namespace Database\Factories\Customer;

//use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Models\Organization;
use Domain\Shared\Factories\Base\BaseFactory;

final class OrganizationFactory extends BaseFactory
{
    protected $model = Organization::class;

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
        $name = fake()->company.' Kft.';
        $taxpayer_id = fake()->regexify('[0-9]{8}-1-[0-9]{2}');
        $taxpayerIdParts = explode('-', $taxpayer_id);

        return [
            'name' => $name,
            'long_name' => $name,
            'taxpayer_id' => $taxpayer_id,
            'vat_code' => $taxpayerIdParts[1],
            'county_code' => $taxpayerIdParts[2],
            'country_code' => 'HU',
            //'org_type' => $faker->randomElement(CorporateForm::getValueArray()),
        ];
    }
}
