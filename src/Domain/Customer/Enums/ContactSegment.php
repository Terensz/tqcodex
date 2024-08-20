<?php

namespace Domain\Customer\Enums;

use Domain\Shared\Traits\EnumToArray;

enum ContactSegment: string
{
    use EnumToArray;

    case PRIVATE = 'Private';

    case SALES = 'Sales';

    case EXECUTIVE = 'Executive';

    case NONE = 'None';

    public function label(): string
    {
        return match ($this) {
            self::PRIVATE => __('finance.Private'),
            self::SALES => __('finance.Sales'),
            self::EXECUTIVE => __('finance.Executive'),
            self::NONE => __('shared.None'),
        };
    }
}

// 'segment' => [new Enum(ContactSegments::class)],
