<?php

namespace Domain\Shared\Helpers;

class DateHelper
{
    public static function isValidTimestamp($timestamp)
    {
        return (bool) PHPHelper::pregMatch('/^\d{10}$/', $timestamp) && (PHPHelper::date('Y-m-d H:i:s', $timestamp) !== false);
    }
}
