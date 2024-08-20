<?php

namespace Domain\Customer\Rules;

use Closure;
// use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\User\Services\UserService;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Password;

class ValidCustomerPasswordResetToken implements ValidationRule
{
    public $contactProfile;

    public $token;

    public function __construct(?ContactProfile $contactProfile, $token)
    {
        $this->contactProfile = $contactProfile;
        $this->token = $token;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $broker = null;
        if ($this->contactProfile && $this->token) {
            $broker = Password::broker(UserService::getAuthProvider(UserService::ROLE_TYPE_CUSTOMER));
        }

        /**
         * Ezzel sokat szivtam...
         * Ha barmi baj lenne vele:
         * a $broker ez: Domain\User\Passwords\PasswordBroker
         * a Contact-ok tokenjeit pedig ez a class kezeli: Domain\User\Passwords\DatabaseTokenRepository .
         */
        if (! $this->contactProfile || ! $this->contactProfile->getContact() || ! $this->token || ! $broker instanceof PasswordBroker || ! $broker->tokenExists($this->contactProfile->getContact(), $this->token)) {
            $fail(__('passwords.InvalidToken'));
        }
    }
}
