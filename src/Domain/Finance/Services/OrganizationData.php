<?php

namespace Domain\Finance\Services;

class OrganizationData
{
    public $taxpayerId;

    public $vatCode;

    public $countyCode;

    public $shortName;

    public $longName;

    public $addresses;

    public static function fromArray(array $array)
    {
        $instance = new self;
        $instance->taxpayerId = isset($array['taxpayerId']) ? $array['taxpayerId'] : null;
        $instance->vatCode = isset($array['vatCode']) ? $array['vatCode'] : null;
        $instance->countyCode = isset($array['countyCode']) ? $array['countyCode'] : null;
        $instance->shortName = isset($array['shortName']) ? $array['shortName'] : null;
        $instance->longName = isset($array['longName']) ? $array['longName'] : null;

        if (isset($array['addresses']) && is_array($array['addresses'])) {
            $addresses = [];
            foreach ($array['addresses'] as $addressItem) {
                $addresses[] = OrganizationAddressData::fromArray($addressItem);
            }

            $instance->addresses = collect($addresses);
        }

        return $instance;
    }

    public static function fromXml($xml, $maxAddressesCount = null)
    {
        // dump($xml);
        $instance = new self;
        $instance->taxpayerId = (string) $xml->taxNumberDetail->taxpayerId;
        $instance->vatCode = (string) $xml->taxNumberDetail->vatCode;
        $instance->countyCode = (string) $xml->taxNumberDetail->countyCode;
        $instance->shortName = (string) $xml->taxpayerShortName;
        $instance->longName = (string) $xml->taxpayerName;

        // Végigmegyünk az összes címen
        $addresses = [];
        $addressCounter = 0;
        foreach ($xml->taxpayerAddressList->taxpayerAddressItem as $addressItem) {
            if ($maxAddressesCount && $addressCounter <= $maxAddressesCount) {
                $addresses[] = OrganizationAddressData::fromXml($addressItem->taxpayerAddress);
            }
            $addressCounter++;
        }

        $instance->addresses = collect($addresses);

        return $instance;
    }
}
