<?php

namespace Domain\Admin\Enums;

use Domain\Shared\Traits\EnumToArray;

enum AdminStatus: string
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
