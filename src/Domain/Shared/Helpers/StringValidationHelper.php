<?php

namespace Domain\Shared\Helpers;

class StringValidationHelper
{
    public static function hasMixedCharacters($string, $mustContainSpecialChar = false)
    {
        // If it's not a string or a number, than we cannot check anything.
        if (! is_string($string) && ! is_numeric($string)) {
            return false;
        }

        // String must contain at least one number, one lowercase and one uppercase.
        if (! preg_match('/[a-z]/', $string) || ! preg_match('/[A-Z]/', $string) || ! preg_match('/[0-9]/', $string)) {
            return false;
        }

        // If the string should also contain a special character
        if ($mustContainSpecialChar && ! preg_match('/[^a-zA-Z0-9]/', $string)) {
            return false;
        }

        // Minden ellenőrzés sikeres, tehát true
        return true;
    }
}
