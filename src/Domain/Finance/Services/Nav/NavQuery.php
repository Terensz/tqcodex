<?php

namespace Domain\Finance\Services\Nav;

use Domain\Customer\Services\OrganizationTestDataService;
use Domain\Finance\Services\OrganizationData;
use Exception;
use Illuminate\Support\Facades\Log;

class NavQuery
{
    public static $apiBaseUrl;

    public static $apiUserData;

    public static $apiSoftwareData;

    public static function getApiBaseUrl(): string
    {
        if (! self::$apiBaseUrl) {
            self::$apiBaseUrl = env('NAV_API_BASE_URL');
        }

        return self::$apiBaseUrl;
    }

    public static function getApiUserData(): array
    {
        if (! self::$apiUserData) {
            self::$apiUserData = [
                'login' => env('NAV_USER_USERNAME'),
                'password' => env('NAV_USER_PASSWORD'),
                'taxNumber' => env('NAV_USER_SHORT_TAXNUMBER'),
                'signKey' => env('NAV_USER_SIGN_KEY'),
                'exchangeKey' => env('NAV_USER_EXCHANGE_KEY'),
            ];
        }

        return self::$apiUserData;
    }

    public static function getApiSoftwareData(): array
    {
        if (! self::$apiSoftwareData) {
            self::$apiSoftwareData = [
                'softwareId' => env('NAV_SOFTWARE_ID'),
                'softwareName' => env('NAV_SOFTWARE_NAME'),
                'softwareOperation' => env('NAV_SOFTWARE_OPERATION'),
                'softwareMainVersion' => env('NAV_SOFTWARE_MAIN_VERSION'),
                'softwareDevName' => env('NAV_SOFTWARE_DEV_NAME'),
                'softwareDevContact' => env('NAV_SOFTWARE_DEV_CONTACT'),
                'softwareDevCountryCode' => env('NAV_SOFTWARE_DEV_COUNTRY_CODE'),
                'softwareDevTaxNumber' => env('NAV_SOFTWARE_DEV_TAX_NUMBER'),
            ];
        }

        return self::$apiSoftwareData;
    }

    /**
     * @throws Exception
     */
    public static function queryTaxpayer($taxpayerId, $maxAddressesCount = null): ?OrganizationData
    {
        try {
            if (app()->environment('local') || app()->environment('testing')) {
                // dump('testing! (no API called)');
                $testData = OrganizationTestDataService::getOrgAPIData($taxpayerId);
                $result = null;
                if ($testData) {
                    $result = OrganizationData::fromArray($testData);
                }

            } else {
                $config = new \Domain\Finance\Services\Nav\Src\Config(self::getApiBaseUrl(), self::getApiUserData(), self::getApiSoftwareData());
                $reporter = new \Domain\Finance\Services\Nav\Src\Reporter($config);

                $result = null;
                $resultXml = $reporter->queryTaxpayer($taxpayerId);
                if ($resultXml) {
                    $result = OrganizationData::fromXml($resultXml, $maxAddressesCount);
                }
            }

            return $result;
        } catch (Exception $e) {
            Log::error('src/Domain/Project/Livewire/CompensationItemBulkUpload.php '.$e);
            throw $e;
        }
    }
}
