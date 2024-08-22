<?php

namespace Domain\Admin\Rules;

use Closure;
use Domain\Admin\Models\Admin;
use Domain\User\Services\UserService;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Password;

class ValidAdminPasswordResetToken implements ValidationRule
{
    public $user;

    public $token;

    public function __construct(?Admin $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dump($this->token);exit;
        $broker = null;
        if ($this->user && $this->token) {
            $broker = Password::broker(UserService::getAuthProvider(UserService::ROLE_TYPE_ADMIN));
        }
        if (! $this->user || ! $this->token || ! $broker instanceof PasswordBroker || ! $broker->tokenExists($this->user, $this->token)) {
            $fail(__('passwords.InvalidToken'));
        }
    }
}
