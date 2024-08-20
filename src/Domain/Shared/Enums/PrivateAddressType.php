<?php

namespace Domain\Shared\Enums;

use Domain\Shared\Traits\EnumToArray;

enum PrivateAddressType: string
{
    use EnumToArray;

    case HOME = 'Home'; // Lakcím

    case BILLING = 'Billing'; // Számlázási cím

    case TEMPORARY = 'Temporary'; // Ideiglenes cím

    public function label(): string
    {
        return match ($this) {
            self::HOME => __('shared.Home'),
            self::BILLING => __('shared.Billing'),
            self::TEMPORARY => __('shared.Temporary'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
