<?php

declare(strict_types=1);

namespace Domain\Project\Rules;

use Domain\Customer\Rules\ContactRules;

class ContactRegisterRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        $rules = ContactRules::rules();

        return $rules;
    }
}
