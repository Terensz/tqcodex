<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\App;

class LocaleHelper
{
    public const LOCALE_HU = 'hu';

    public static function getAppLocale()
    {
        return App::getLocale();
    }

    public static function appLocaleEquals($locale)
    {
        return PHPHelper::toLowercase(App::getLocale()) === PHPHelper::toLowercase($locale);
    }
}
