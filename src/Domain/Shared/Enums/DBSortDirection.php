<?php

namespace Domain\Shared\Enums;

use Domain\Shared\Traits\EnumToArray;

enum DBSortDirection: string
{
    use EnumToArray;

    case ASC = 'asc';

    case DESC = 'desc';

    public function label(): string
    {
        return match ($this) {
            self::ASC => __('shared.SortDirectionAsc'),
            self::DESC => __('shared.SortDirectionDesc'),
        };
    }
}
