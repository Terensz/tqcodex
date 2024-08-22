<?php

namespace Domain\Admin\Rules;

use Closure;
use Domain\Admin\Models\Admin;
use Domain\Admin\Models\AdminToken;
use Domain\Shared\Helpers\TokenHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAdminEmailChangeToken implements ValidationRule
{
    public $admin;

    public $adminToken;

    public $newEmail;

    public function __construct(?Admin $admin, ?AdminToken $userToken, string $newEmail)
    {
        $this->admin = $admin;
        $this->adminToken = $userToken;
        $this->newEmail = $newEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dump($this->userToken);exit;
        if ($this->adminToken && ! TokenHelper::verifyTokenLifetime($this->adminToken->token)) {
            $fail('user.ExpiredToken');
        }

        if (! $this->adminToken || $this->newEmail !== $this->adminToken->email) {
            $fail('passwords.InvalidToken');
        }
    }
}
