<?php

declare(strict_types=1);

use Domain\Shared\Enums\StreetSuffix;

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99,
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '2040',
            'region' => null,
            'city' => 'Budaörs',
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => StreetSuffix::STREET->value,
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Every data filled and correct.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '2040',
            'region' => null,
            'city' => 'Budaörs',
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => StreetSuffix::STREET->value,
            'number' => '34.',
            'building' => null,
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Missing building.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '2040',
            'region' => null,
            'city' => 'Budaörs',
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => StreetSuffix::STREET->value,
            'number' => '34.',
            'building' => '1.',
            'floor' => null,
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Missing floor.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '2040',
            'region' => null,
            'city' => 'Budaörs',
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => StreetSuffix::STREET->value,
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => null,
            'address' => null,
            'lane' => null,
        ],
        'Missing door.',
    ],
];
dataset('OrgAddressDataOK', $dataSet);
