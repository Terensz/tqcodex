<?php

namespace Domain\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxOnceInArray implements ValidationRule
{
    public $array;

    public $errorMessage;

    public function __construct(array $array, ?string $errorMessage)
    {
        $this->array = $array;
        $this->errorMessage = $errorMessage;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $occurrences = array_count_values($this->array);
        if ($this->errorMessage && $this->array && isset($occurrences[$value]) && $occurrences[$value] > 1) {
            $fail($this->errorMessage);
        }
    }
}
