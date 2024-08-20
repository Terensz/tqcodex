<?php

namespace Domain\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotInArray implements ValidationRule
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
        if ($this->errorMessage && $this->array && in_array($value, $this->array)) {
            $fail($this->errorMessage);
        }
    }
}
