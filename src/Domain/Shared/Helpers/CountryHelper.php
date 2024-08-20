<?php

namespace Domain\Shared\Helpers;

use Domain\Shared\Models\Country;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CountryHelper
{
    public static function asSelectArray($addPleaseChooseOption = true): Collection
    {
        $values = [];

        if ($addPleaseChooseOption) {
            $pleaseChooseOption = [
                [
                    ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
                    ValidationHelper::OPTION_VALUE => null,
                ],
            ];
            $values = array_merge($pleaseChooseOption, $values);
        }

        $countries = Cache::remember('country_list_all', 3600, function () {
            return Country::all();
        });

        foreach ($countries as $country) {
            $values[] = [
                ValidationHelper::OPTION_LABEL => $country->label(),
                ValidationHelper::OPTION_VALUE => $country->id,
            ];
        }

        return collect($values);
    }

    public static function getCountryObjectByCode($countryCode)
    {
        return Cache::remember('country_code_'.$countryCode, 3600, function () use ($countryCode) {
            return Country::where(['iso2' => $countryCode])->first();
        });
    }

    public static function getCountryObjectById($countryId)
    {
        return Cache::remember('country_id_'.$countryId, 3600, function () use ($countryId) {
            return Country::where(['id' => $countryId])->first();
        });
    }
}
