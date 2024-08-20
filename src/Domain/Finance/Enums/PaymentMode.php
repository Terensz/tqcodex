<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum PaymentMode: string
{
    use EnumToArray;

    case TRANSFER = 'Transfer';

    public function label(): string
    {
        return match ($this) {
            self::TRANSFER => __('finance.Transfer'),
        };
    }
}
