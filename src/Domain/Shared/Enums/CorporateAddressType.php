<?php

namespace Domain\Shared\Enums;

use Domain\Shared\Traits\EnumToArray;

enum CorporateAddressType: string
{
    use EnumToArray;

    case HEADQUARTERS = 'Headquarters'; // Székhely

    case SITE = 'Site'; // Telephely

    case BILLING = 'Billing'; // Számlázási cím

    case DELIVERY_POINT = 'DeliveryPoint'; // Átvevőpont

    case CONTACT_CENTER = 'ContactCenter'; // Ügyfélszolgálat

    case TEMPORARY = 'Temporary'; // Ideiglenes cím

    public function label(): string
    {
        return match ($this) {
            self::HEADQUARTERS => __('shared.Headquarters'),
            self::SITE => __('shared.Site'),
            self::BILLING => __('shared.Billing'),
            self::DELIVERY_POINT => __('shared.DeliveryPoint'),
            self::CONTACT_CENTER => __('shared.ContactCenter'),
            self::TEMPORARY => __('shared.Temporary'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
