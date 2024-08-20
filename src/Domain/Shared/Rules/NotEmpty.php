<?php

namespace Domain\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotEmpty implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            // $fail('alma!!! '.$value);
            // Ha már létezik felhasználó ezzel az e-mail címmel, hibát adunk vissza
            $fail(__('validation.ItemIsRequired', ['item' => $value]));
        }
    }
}
