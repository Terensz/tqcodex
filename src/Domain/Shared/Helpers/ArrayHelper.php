<?php

namespace Domain\Shared\Helpers;

class ArrayHelper
{
    /**
     * Extract specified keys from an array.
     */
    public static function extractByKeys(array $keys, array $array): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Remove specified keys from an array.
     */
    public static function removeByKeys(array $keys, array &$array): array
    {
        foreach ($keys as $key) {
            unset($array[$key]);
        }

        return $array;
    }
}
