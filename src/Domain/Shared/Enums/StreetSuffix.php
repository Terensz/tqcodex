<?php

namespace Domain\Shared\Enums;

use Domain\Shared\Traits\EnumToArray;

enum StreetSuffix: string
{
    use EnumToArray;

    case ROAD = 'Road'; // Út

    case STREET = 'Street'; // Utca

    case SQUARE = 'Square'; // Tér

    case AVENUE = 'Avenue'; // Sugárút

    case BOULEVARD = 'Boulevard'; // Körút

    case STEPS = 'Steps'; // Lépcső

    case PARK = 'Park'; // Park

    case DRIVE = 'Drive'; // Főút. Benzinkutak címe pl.

    case HIGHWAY = 'Highway'; // Autópálya

    case PROMENADE = 'Promenade'; // Sétány

    case ROW = 'Row'; // Sor

    case ALLEY = 'Alley'; // Köz

    public function label(): string
    {
        return match ($this) {
            self::ROAD => __('shared.Road'),
            self::STREET => __('shared.Street'),
            self::SQUARE => __('shared.Square'),
            self::AVENUE => __('shared.Avenue'),
            self::BOULEVARD => __('shared.Boulevard'),
            self::STEPS => __('shared.Steps'),
            self::PARK => __('shared.Park'),
            self::DRIVE => __('shared.Drive'),
            self::HIGHWAY => __('shared.Highway'),
            self::PROMENADE => __('shared.Promenade'),
            self::ROW => __('shared.Row'),
            self::ALLEY => __('shared.Alley'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
