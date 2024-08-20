<?php

namespace Domain\Admin\Rules;

use Closure;
use Domain\Admin\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAdminOldEmail implements ValidationRule
{
    public $user;

    public $oldEmail;

    public function __construct(?User $user, string $oldEmail)
    {
        $this->user = $user;
        $this->oldEmail = $oldEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->user && $this->user->email !== $this->oldEmail) {
            $fail('user.OldEmailIsInproper');
        }
    }
}
