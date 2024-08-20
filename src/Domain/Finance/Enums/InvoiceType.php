<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum InvoiceType: string
{
    use EnumToArray;

    case DEBT = 'Debt';

    case CLAIM = 'Claim';

    public function label(): string
    {
        return match ($this) {
            self::DEBT => __('finance.Debt'),
            self::CLAIM => __('finance.Claim'),
        };
    }

    // public static function asSelectArray()
    // {
    //     $values = collect(self::cases())
    //         ->map(function ($enum) {
    //             return [
    //                 'label' => $enum->label(),
    //                 'value' => $enum->value
    //             ];
    //         });

    //     return $values;
    // }
}
