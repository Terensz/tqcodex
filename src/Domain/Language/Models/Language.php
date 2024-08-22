<?php

namespace Domain\Language\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Support\Facades\Cache;

class Language extends BaseModel
{
    public const CACHE_KEY = 'system_languages';

    public const LANGUAGE_HUNGARIAN = 'hu';

    public const LANGUAGE_ENGLISH = 'en';

    public $timestamps = false;

    // public function getIsDefaultReadableAttribute()
    // {
    //     return $this->is_default ? __('shared.bool.Yes') : __('shared.bool.No');
    // }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            static::forgetSystemLanguages();
            static::getSystemLanguages();
        });

        static::updated(function () {
            static::forgetSystemLanguages();
            static::getSystemLanguages();
        });

        static::deleted(function () {
            static::forgetSystemLanguages();
            static::getSystemLanguages();
        });
    }

    public static function getSystemLanguages()
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => Language::orderBy('is_default', 'desc')->get());
    }

    public static function forgetSystemLanguages()
    {
        return Cache::forget(self::CACHE_KEY);
    }
}
