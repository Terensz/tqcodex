<?php

namespace Domain\Shared\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class TokenHelper
{
    public const TOKEN_MAX_LIFETIME_SECONDS = 600;

    public static $tokenPartsSeparator = '_';

    public static function generateToken(int $length = 24, bool $addTimestamp = true): string
    {
        $tokenBase = Str::random($length);

        if ($addTimestamp) {
            $timestamp = Carbon::now()->timestamp;
            $tokenBase .= '_'.$timestamp;
        }

        return self::encodeToken($tokenBase);
    }

    public static function encodeToken(string $token): string
    {
        return base64_encode($token);
    }

    public static function decodeToken(string $token): string
    {
        return base64_decode($token);
    }

    public static function verifyTokenLifetime(string $token, $timestampAdded = true): bool
    {
        $token = self::decodeToken($token);

        if ($timestampAdded) {
            $parts = explode(self::$tokenPartsSeparator, $token);
            if (count($parts) !== 2 || ! DateHelper::isValidTimestamp($parts[1])) {
                return false;
            }
            $token = $parts[0];
            $timestamp = $parts[1];

            $currentTime = Carbon::now()->timestamp;
            if (((int) $currentTime - (int) $timestamp) > self::TOKEN_MAX_LIFETIME_SECONDS) {
                return false;
            }
        }

        return true;
    }

    // public static function getCodePart(string $token, $timestampAdded = true) : string
    // {
    //     $token = self::decodeToken($token);

    //     if ($timestampAdded) {
    //         $parts = explode(self::$tokenPartsSeparator, $token);
    //         if (count($parts) === 2) {
    //             $token = $parts[0];
    //         }
    //     }

    //     return $token;
    // }
}
