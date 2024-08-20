<?php

namespace Domain\Shared\Rules;

use Closure;
use Domain\Shared\Helpers\StringValidationHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class HasMixedCharacters implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! StringValidationHelper::hasMixedCharacters($value, false)) {
            $fail('validation.MustContainOneOfEach')->translate();
        }
    }
}
