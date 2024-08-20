<?php

namespace Domain\Customer\Rules;

use Closure;
use Domain\Customer\Models\ContactToken;
use Domain\Shared\Helpers\TokenHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCustomerEmailChangeToken implements ValidationRule
{
    // public $user;

    public $userToken;

    public $newEmail;

    public function __construct(?ContactToken $userToken, string $newEmail)
    {
        // $this->user = $user;
        $this->userToken = $userToken;
        $this->newEmail = $newEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dump($this->userToken->token);exit;
        if ($this->userToken && ! TokenHelper::verifyTokenLifetime($this->userToken->token)) {
            $fail(__('user.ExpiredToken'));
        } elseif (! $this->userToken || $this->newEmail !== $this->userToken->email) {
            $fail(__('user.InvalidEmailChangeToken'));
        }
    }
}
