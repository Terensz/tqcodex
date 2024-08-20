<?php

namespace Domain\Admin\Rules;

use Domain\Admin\Models\User;
use Domain\Shared\Rules\HasMixedCharacters;
use Illuminate\Validation\Rule;

class UserRules
{
    public static function rules($ignoreUserId = null)
    {
        $emailUniqueRule = Rule::unique('users', 'email');
        if ($ignoreUserId && User::find($ignoreUserId)) {
            $emailUniqueRule = $emailUniqueRule->ignore($ignoreUserId);
        }

        return [
            'firstname' => [
                'required',
            ],
            'lastname' => [
                'required',
            ],
            'email' => [
                'required',
                'min:5',
                'email',
                $emailUniqueRule,
            ],
            'profile_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
            'technicalPassword' => [
                'min:12',
                new HasMixedCharacters,
            ],
        ];
    }
}
