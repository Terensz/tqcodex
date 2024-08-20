<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum VatCode: int
{
    use EnumToArray;

    case TYPE_1 = 1;

    case TYPE_2 = 2;

    case TYPE_3 = 3;

    case TYPE_4 = 4;

    case TYPE_5 = 5;

    public function label(): string
    {
        return match ($this) {
            self::TYPE_1 => __('finance.VatCode1'),
            self::TYPE_2 => __('finance.VatCode2'),
            self::TYPE_3 => __('finance.VatCode3'),
            self::TYPE_4 => __('finance.VatCode4'),
            self::TYPE_5 => __('finance.VatCode5'),
        };
    }
}
