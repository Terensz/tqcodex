<?php

namespace Domain\Customer\Enums;

use Domain\Shared\Traits\EnumToArray;

enum BusinessType: string
{
    use EnumToArray;

    case B2C = 'B2c';

    case B2B = 'B2b';

    public function label(): string
    {
        return match ($this) {
            self::B2C => __('finance.B2c'),
            self::B2B => __('finance.B2B'),
        };
    }
}
