<?php

namespace Domain\Customer\Enums;

use Domain\Shared\Traits\EnumToArray;

enum Location: string
{
    use EnumToArray;

    case HU = 'HU';

    case EU = 'EU';

    case EUK = 'EUK';

    case NONE = 'none';

    public function label(): string
    {
        return match ($this) {
            self::HU => __('shared.Hungary'),
            self::EU => __('shared.EU'),
            self::EUK => __('shared.EUK'),
            self::NONE => __('shared.None'),
        };
    }
}
