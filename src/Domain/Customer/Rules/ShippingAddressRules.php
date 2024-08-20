<?php

declare(strict_types=1);

namespace Domain\Customer\Rules;

class ShippingAddressRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'shipping_contact' => ['required_without:shipping_is_same_as_billing', 'string', 'max:255'],
            'shipping_company' => ['nullable', 'sometimes', 'string', 'max:255'],
            'shipping_email' => ['required_without:shipping_is_same_as_billing', 'email:rfc,dns'],
            'shipping_phone' => ['required_without:shipping_is_same_as_billing', 'phone:INTERNATIONAL,HU'],
            'shipping_phone_country' => ['required_with:shipping_phone'],
            'shipping_country_select' => ['required_without:shipping_is_same_as_billing', 'string', 'max:255'],
            'shipping_postal_code' => ['required_without:shipping_is_same_as_billing', 'string', 'max:16'],
            'shipping_city' => ['required_without:shipping_is_same_as_billing', 'string', 'max:255'],
            'shipping_lane' => ['required_without:shipping_is_same_as_billing', 'string', 'max:255'],
            'shipping_region' => ['nullable', 'sometimes', 'string', 'max:255'],
        ];
    }
}
