<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum PublicCurrency: string
{
    use EnumToArray;

    case HUF = 'HUF';

    case EUR = 'EUR';

    case USD = 'USD';

    public function label(): string
    {
        return match ($this) {
            self::HUF => __('finance.HUF'),
            self::EUR => __('finance.EUR'),
            self::USD => __('finance.USD'),
        };
    }
}
