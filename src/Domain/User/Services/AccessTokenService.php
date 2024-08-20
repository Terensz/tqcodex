<?php

namespace Domain\User\Services;

use Illuminate\Support\Facades\Session;

class AccessTokenService
{
    public static function createAccessToken()
    {
        return bin2hex(random_bytes(32));
    }

    public static function getAccessTokenName($roleType)
    {
        return $roleType.'_access_token';
    }

    public static function getAccessToken($roleType)
    {
        return Session::get(self::getAccessTokenName($roleType));
    }

    public static function isAccessToken($roleType)
    {
        return Session::has(self::getAccessTokenName($roleType));
    }

    public static function setAccessToken($roleType, $token)
    {
        Session::put(self::getAccessTokenName($roleType), $token);
    }

    public static function validateAccessToken($token)
    {
        // Access token ellenőrzése
    }
}
