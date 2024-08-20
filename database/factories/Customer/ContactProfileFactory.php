<?php

declare(strict_types=1);

namespace Database\Factories\Customer;

use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Factories\Base\BaseFactory;

final class ContactProfileFactory extends BaseFactory
{
    protected $model = ContactProfile::class;

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
        return [
            'lastname' => fake()->lastName(),
            'firstname' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->unique()->phoneNumber(),
        ];
    }
}
