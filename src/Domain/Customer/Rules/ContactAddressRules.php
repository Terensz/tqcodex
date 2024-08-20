<?php

declare(strict_types=1);

namespace Domain\Customer\Rules;

class ContactAddressRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'country_select' => ['sometimes', 'string', 'max:255'],
            'region' => ['nullable', 'sometimes', 'string', 'max:255'],
            'lane' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'title' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
