<?php

namespace Domain\Customer\Enums;

use Domain\Shared\Traits\EnumToArray;

enum ContactStatus: string
{
    use EnumToArray;

    case ACTIVE = 'Active';

    case INACTIVE = 'Inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('shared.Active'),
            self::INACTIVE => __('shared.Inactive'),
        };
    }
}
