<?php

namespace Domain\Admin\Rules;

use Closure;
use Domain\Admin\Models\Admin;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAdminOldEmail implements ValidationRule
{
    public $admin;

    public $oldEmail;

    public function __construct(?Admin $admin, string $oldEmail)
    {
        $this->admin = $admin;
        $this->oldEmail = $oldEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->admin && $this->admin->email !== $this->oldEmail) {
            $fail('user.OldEmailIsInproper');
        }
    }
}
