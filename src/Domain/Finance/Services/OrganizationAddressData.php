<?php

namespace Domain\Finance\Services;

class OrganizationAddressData
{
    public $countryCode;

    public $postalCode;

    public $city;

    public $streetName;

    public $publicPlaceCategory;

    public $houseNumber;

    public $floor;

    public $door;

    public static function fromArray(array $array)
    {
        $instance = new self;
        $instance->countryCode = isset($array['countryCode']) ? $array['countryCode'] : null;
        $instance->postalCode = isset($array['postalCode']) ? $array['postalCode'] : null;
        $instance->city = isset($array['city']) ? $array['city'] : null;
        $instance->streetName = isset($array['streetName']) ? $array['streetName'] : null;
        $instance->publicPlaceCategory = isset($array['publicPlaceCategory']) ? $array['publicPlaceCategory'] : null;
        $instance->houseNumber = isset($array['houseNumber']) ? $array['houseNumber'] : null;
        $instance->floor = isset($array['floor']) ? $array['floor'] : null;
        $instance->door = isset($array['door']) ? $array['door'] : null;

        return $instance;
    }

    public static function fromXml($xml)
    {
        $instance = new self;
        $instance->countryCode = (string) $xml->countryCode;
        $instance->postalCode = (string) $xml->postalCode;
        $instance->city = (string) $xml->city;
        $instance->streetName = (string) $xml->streetName;
        $instance->publicPlaceCategory = (string) $xml->publicPlaceCategory;
        $instance->houseNumber = (string) $xml->number;
        $instance->floor = isset($xml->floor) ? (string) $xml->floor : null;
        $instance->door = isset($xml->door) ? (string) $xml->door : null;

        return $instance;
    }
}
