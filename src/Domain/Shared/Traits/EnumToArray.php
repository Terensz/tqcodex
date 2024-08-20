<?php

declare(strict_types=1);

namespace Domain\Shared\Traits;

use Domain\Shared\Helpers\ValidationHelper;

trait EnumToArray
{
    public static function asSelectArray($addPleaseChooseOption = true)
    {
        $values = collect(self::cases())
            ->map(function ($enum) {
                return [
                    ValidationHelper::OPTION_LABEL => $enum->label(),
                    ValidationHelper::OPTION_VALUE => $enum->value,
                ];
            });

        if ($addPleaseChooseOption) {
            $pleaseChooseOption = collect([
                [
                    ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
                    ValidationHelper::OPTION_VALUE => null,
                ],
            ]);
            $values = $pleaseChooseOption->merge($values);
        }

        return $values;
    }

    public static function getValueArray()
    {
        return collect(self::cases())
            ->map(function ($enum) {
                return $enum->value;
            });
    }

    // public static function names(): array
    // {
    //     return array_column(self::cases(), 'name');
    // }

    // public static function values(): array
    // {
    //     return array_column(self::cases(), 'value');
    // }

    // public static function translatedValues($translationFile, $arrangeToLivewireFormat = false, $addPleaseChooseOption = true): array
    // {
    //     $array = self::array();
    //     $livewireFormatResult = [];
    //     $normalResult = [];

    //     if ($arrangeToLivewireFormat && $addPleaseChooseOption) {
    //         $livewireFormatResult[] = [
    //             ValidationHelper::OPTION_VALUE => null,
    //             ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
    //         ];
    //     }

    //     foreach ($array as $key => &$value) {
    //         $displayed = __($translationFile.'.'.$value);
    //         if ($arrangeToLivewireFormat) {
    //             $livewireFormatResult[] = [
    //                 ValidationHelper::OPTION_VALUE => $value,
    //                 ValidationHelper::OPTION_LABEL => $displayed,
    //             ];
    //         } else {
    //             $normalResult[] = $displayed;
    //         }
    //     }

    //     return $arrangeToLivewireFormat ? $livewireFormatResult : $normalResult;
    // }

    // public static function array(): array
    // {
    //     return array_combine(self::names(), self::values());
    // }

    // public static function invertedArray(): array
    // {
    //     return array_combine(self::values(), self::names());
    // }
}
