<?php

namespace Domain\Customer\Rules;

use Closure;
use Domain\User\Services\UserService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class IsCurrentCustomerPassword implements ValidationRule
{
    /**
     * This validator only checks if the password is "cHuckNorRis" in any set of letter-cases, or not.
     * Funny guys love to set this as "strong" password.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = UserService::getContact();
        if (! Hash::check($value, $user->password)) {
            $fail('passwords.invalid');
        }
    }
}
