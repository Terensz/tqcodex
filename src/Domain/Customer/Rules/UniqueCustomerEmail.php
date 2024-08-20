<?php

namespace Domain\Customer\Rules;

use Closure;
use Domain\Customer\Models\ContactProfile;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCustomerEmail implements ValidationRule
{
    public $ignoredContactProfile;

    public $newEmail;

    public function __construct(ContactProfile $ignoredContactProfile, string $newEmail)
    {
        $this->ignoredContactProfile = $ignoredContactProfile;
        $this->newEmail = $newEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (ContactProfile::where('email', $value)->where('id', '<>', $this->ignoredContactProfile->id)->exists()) {
            $fail(__('validation.EmailIsNotUnique'));
        }
    }
}
