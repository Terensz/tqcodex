<?php

declare(strict_types=1);

namespace Database\Factories\Shared;

use Domain\Admin\Models\Userip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Admin\Models\Userip>
 */
final class UseripFactory extends Factory
{
    protected $model = Userip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

        ];
    }
}
