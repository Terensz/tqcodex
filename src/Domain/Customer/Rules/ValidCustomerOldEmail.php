<?php

namespace Domain\Customer\Rules;

use Closure;
// use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCustomerOldEmail implements ValidationRule
{
    public $contactProfile;

    public $oldEmail;

    public function __construct(?ContactProfile $contactProfile, string $oldEmail)
    {
        $this->contactProfile = $contactProfile;
        $this->oldEmail = $oldEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->contactProfile && $this->contactProfile->email !== $this->oldEmail) {
            $fail('user.OldEmailIsInproper');
        }
    }
}
