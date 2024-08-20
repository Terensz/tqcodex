<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum Currency: string
{
    use EnumToArray;

    case HUF = 'HUF';

    case EUR = 'EUR';

    case USD = 'USD';

    case STK = 'STK';

    public function label(): string
    {
        return match ($this) {
            self::HUF => __('finance.HUF'),
            self::EUR => __('finance.EUR'),
            self::USD => __('finance.USD'),
            self::STK => __('finance.STK'),
        };
    }
}
