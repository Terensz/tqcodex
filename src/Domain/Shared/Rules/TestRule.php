<?php

namespace Domain\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TestRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtoupper($value) !== $value) {
            $fail('The :attribute must be uppercase.');
        }
    }
}
