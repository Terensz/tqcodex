<?php

namespace Domain\Finance\Enums;

use Domain\Shared\Traits\EnumToArray;

enum CompensationItemStatus: string
{
    use EnumToArray;

    case FREE = 'Free';

    case PENDING = 'Pending';

    // case FINISHED = 'Finished';

    case FULLY_USED = 'FullyUsed';

    case PARTIALLY_USED = 'PartiallyUsed';

    public function label(): string
    {
        return match ($this) {
            self::FREE => __('finance.Free'),
            self::PENDING => __('finance.Pending'),
            self::FULLY_USED => __('finance.FullyUsed'),
            self::PARTIALLY_USED => __('finance.PartiallyUsed'),
        };
    }
}
