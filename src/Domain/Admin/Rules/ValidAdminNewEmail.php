<?php

namespace Domain\Admin\Rules;

use Closure;
use Domain\Admin\Models\AdminToken;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAdminNewEmail implements ValidationRule
{
    public $userToken;

    public $newEmail;

    public function __construct(?AdminToken $userToken, string $newEmail)
    {
        $this->userToken = $userToken;
        $this->newEmail = $newEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->userToken && $this->userToken->email !== $this->newEmail) {
            $fail('user.NewEmailIsInproper');
        }
    }
}
