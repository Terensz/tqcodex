<?php

namespace Domain\Shared\Helpers;

// use Illuminate\Support\Str;

class PHPHelper
{
    public static function isString($input): bool
    {
        return is_string($input);
    }

    public static function isArray($input): bool
    {
        return is_array($input);
    }

    public static function arrayKeys($array): ?array
    {
        return (! $array || ! PHPHelper::isArray($array)) ? null : array_keys($array);
    }

    public static function urlDecode($encededUrl): ?string
    {
        return (! $encededUrl || ! PHPHelper::isString($encededUrl)) ? null : urldecode($encededUrl);
    }

    public static function inArray($needle, $haystack): bool
    {
        return PHPHelper::isArray($haystack) && in_array($needle, $haystack);
    }

    public static function ceil($number)
    {
        return ceil($number);
    }

    public static function strContains($haystack, $needle)
    {
        return str_contains($haystack, $needle);
    }

    public static function date($format, $value = null)
    {
        return date($format, $value);
    }

    public static function pregMatch($pattern, $text)
    {
        return preg_match($pattern, $text);
    }

    public static function arrayMerge($array1, $array2)
    {
        return array_merge($array1, $array2);
    }

    public static function explode($separator, $array)
    {
        return explode($separator, $array);
    }

    public static function implode($separator, $array, $orString = 'OR')
    {
        $result = [];

        foreach ($array as $item) {
            if (is_array($item)) {
                // Ha az elem tömb és van megadott orString, akkor ezzel fűzzük össze a tömb elemeit
                $result[] = implode($orString, $item);
            } else {
                // Egyébként csak hozzáadjuk az elemet a result tömbhöz
                $result[] = $item;
            }
        }

        return implode($separator, $result);
        // return implode($separator, $array);
    }

    public static function arraySlice($array, $index)
    {
        return array_slice($array, $index);
    }

    public static function trim(string $string, string $characters = " \n\r\t\v\x00")
    {
        return trim($string, $characters);
    }

    public static function leftCut(string $string, string $characters = " \n\r\t\v\x00")
    {
        $numChars = strlen($characters);

        return substr($string, $numChars);
    }

    public static function getStringPosition(string $needle, string $haystack)
    {
        return strpos($haystack, $needle);
    }

    public static function toLowercase(string $string)
    {
        return strtolower($string);
    }
}
