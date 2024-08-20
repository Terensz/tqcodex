<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\Session;

class SessionHelper
{
    // private static $salt;

    // private static function getSalt(): string
    // {
    //     if (! self::$salt) {
    //         self::$salt = StringHelper::generateRandomString();
    //     }

    //     return self::$salt;
    // }

    private static function getSaltedKey($key): string
    {
        return $key;
        // .'_'.self::getSalt();
    }

    public static function set($key, $value)
    {
        Session::put(self::getSaltedKey($key), $value);
    }

    public static function get($key)
    {
        return Session::get(self::getSaltedKey($key));
    }

    public static function unset($key)
    {
        return Session::remove(self::getSaltedKey($key));
    }
}
