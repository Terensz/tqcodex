<?php

namespace Domain\Shared\Enums;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Traits\EnumToArray;

enum TrueOrFalse: string
{
    use EnumToArray;

    case TRUE = 'True';

    case FALSE = 'False';

    public function label(): string
    {
        return match ($this) {
            self::TRUE => __('shared.True'),
            self::FALSE => __('shared.False'),
        };
    }

    // public static function asSelectArray_2($addPleaseChooseOption = true)
    // {
    //     $values = collect(self::cases())
    //         ->map(function ($enum) {
    //             return [
    //                 ValidationHelper::OPTION_LABEL => $enum->label(),
    //                 ValidationHelper::OPTION_VALUE => $enum->value
    //             ];
    //         });

    //     if ($addPleaseChooseOption) {
    //         $pleaseChooseOption = collect([
    //             [
    //                 ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
    //                 ValidationHelper::OPTION_VALUE => null,
    //             ]
    //         ]);
    //         $values = $pleaseChooseOption->merge($values);
    //     }
    //     // if ($addPleaseChooseOption) {
    //     //     $values = array_merge([
    //     //         ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
    //     //         ValidationHelper::OPTION_VALUE => null
    //     //     ], $values);
    //     // }

    //     return $values;
    // }

    public static function asSelectArray($addPleaseChooseOption = true)
    {
        $values = [];

        if ($addPleaseChooseOption) {
            $values[] = [
                ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
                ValidationHelper::OPTION_VALUE => null,
            ];
        }

        $values[] = [
            ValidationHelper::OPTION_LABEL => __('shared.'.TrueOrFalse::TRUE->value),
            ValidationHelper::OPTION_VALUE => 1,
        ];

        $values[] = [
            ValidationHelper::OPTION_LABEL => __('shared.'.TrueOrFalse::FALSE->value),
            ValidationHelper::OPTION_VALUE => 0,
        ];

        return collect($values);
    }

    public static function getPossibleValidationValues()
    {
        return [0, 1, true, false];
    }
}
