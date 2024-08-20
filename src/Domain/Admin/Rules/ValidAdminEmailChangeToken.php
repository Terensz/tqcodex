<?php

namespace Domain\Admin\Rules;

use Closure;
use Domain\Admin\Models\User;
use Domain\Admin\Models\UserToken;
use Domain\Shared\Helpers\TokenHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAdminEmailChangeToken implements ValidationRule
{
    public $user;

    public $userToken;

    public $newEmail;

    public function __construct(?User $user, ?UserToken $userToken, string $newEmail)
    {
        $this->user = $user;
        $this->userToken = $userToken;
        $this->newEmail = $newEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dump($this->userToken);exit;
        if ($this->userToken && ! TokenHelper::verifyTokenLifetime($this->userToken->token)) {
            $fail('user.ExpiredToken');
        }

        if (! $this->userToken || $this->newEmail !== $this->userToken->email) {
            $fail('passwords.InvalidToken');
        }
    }
}
