<?php

namespace Domain\Customer\Rules;

use Closure;
use Domain\Customer\Models\ContactToken;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCustomerNewEmail implements ValidationRule
{
    public $userToken;

    public $newEmail;

    public function __construct(?ContactToken $userToken, string $newEmail)
    {
        $this->userToken = $userToken;
        $this->newEmail = $newEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->userToken && $this->userToken->email !== $this->newEmail) {
            $fail(__('user.NewEmailIsInproper'));
        }
    }
}
