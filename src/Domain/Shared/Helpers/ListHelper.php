<?php

namespace Domain\Shared\Helpers;

class ListHelper
{
    public const DEFAULT_PER_PAGE = 10;

    public static function getPerPage(): int
    {
        return self::DEFAULT_PER_PAGE;
    }
}
