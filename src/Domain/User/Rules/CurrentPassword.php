<?php

namespace Domain\User\Rules;

use Closure;
use Domain\Admin\Models\Admin;
use Domain\Customer\Models\Contact;
use Domain\User\Services\UserService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CurrentPassword implements ValidationRule
{
    // public $roleType;

    public $user;

    public $passwordTry;

    public function __construct(Contact|Admin|null $user, int|string|null $passwordTry)
    {
        // $this->roleType = $roleType;
        $this->user = $user;
        $this->passwordTry = $passwordTry;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Hash::check($this->passwordTry, $this->user->password)) {
            $fail('user.CurrentPasswordIsInvalid');
        }
        // if ($this->roleType === UserService::ROLE_TYPE_ADMIN && ! Hash::check($this->passwordTry, $this->user->password)) {
        //     $fail('user.CurrentPasswordIsInvalid');
        // }
    }
}
