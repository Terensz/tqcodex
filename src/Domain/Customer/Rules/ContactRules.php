<?php

declare(strict_types=1);

namespace Domain\Customer\Rules;

use Domain\Customer\Enums\BusinessType;
use Domain\Customer\Enums\ContactSegment;
use Domain\Customer\Models\Contact;
use Domain\Shared\Rules\HasMixedCharacters;
use Domain\Shared\Rules\ValidEnum;
use Illuminate\Validation\Rule;

class ContactRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules($ignoreUserId = null): array
    {
        $emailUniqueRule = Rule::unique('admins', 'email');
        if ($ignoreUserId && Contact::find($ignoreUserId)) {
            $emailUniqueRule = $emailUniqueRule->ignore($ignoreUserId);
        }

        return [
            'lastname' => [
                'required',
                'max:100',
            ],
            'firstname' => [
                'required',
                'max:100',
            ],
            'phone' => [
                'nullable',
                'phone:INTERNATIONAL,HU',
            ],
            'mobile' => [
                'required',
                'phone:INTERNATIONAL,HU',
            ],
            'phone_country' => [
                'required_with:phone',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                $emailUniqueRule,
            ],
            'segment' => [
                new ValidEnum(ContactSegment::class),
                // Rule::enum(ContactSegment::class)
            ],
            'type' => [
                'required',
                new ValidEnum(BusinessType::class),
                // Rule::enum(BusinessType::class)
            ],
            'referral' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'terms_ok' => [
                'required',
                'accepted',
            ],
            'news_ok' => [
                'sometimes',
                'accepted',
            ],
            'direct_sales_ok' => [
                'sometimes',
                'accepted',
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
