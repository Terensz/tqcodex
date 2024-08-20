<?php

namespace Domain\Shared\Helpers;

/**
 * This class was made for calculating data which should be, but not exist in the Laravel framework.
 */
class LaravelHelper
{
    public static function getTotalObjectsCount(object $objectCollection): int|false
    {
        if (! method_exists($objectCollection, 'total') || ! method_exists($objectCollection, 'perPage')) {
            return false;
        }

        return $objectCollection->total();
    }

    public static function getTotalPagesCount(object $objectCollection): int|false
    {
        if (! method_exists($objectCollection, 'total') || ! method_exists($objectCollection, 'perPage')) {
            return false;
        }
        $pages = $objectCollection->total() / $objectCollection->perPage();

        return PHPHelper::ceil($pages);
    }
}
