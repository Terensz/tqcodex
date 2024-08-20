<?php

declare(strict_types=1);

use Domain\Shared\Enums\StreetSuffix;

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'organization_id' => null,
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
        'Missing organization_id.',
    ],
    [
        [
            'organization_id' => 'alma',
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
        'Invalid organization_id.',
    ],
    [
        [
            'organization_id' => 2, // Unauthorized for this user
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
        'Unauthorized organization_id.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => null,
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
        'Missing country_id.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 12345, // Not existing
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
        'Not existing country_id.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => null,
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
        'Missing title.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => null,
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
        'Missing main.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => 'alma',
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '2040',
            'region' => null,
            'city' => 'Budaörs',
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => 'utca',
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Invalid main.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => null,
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
        'Missing postal_code.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '562',
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
        'Too short postal_code.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => '24519',
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
        'Too long postal_code.',
    ],
    [
        [
            'organization_id' => 4, // TRIEX KFT
            'country_id' => 99, // Magyarország
            'title' => 'Próba NYRT. - átvevőpont',
            'main' => false,
            'address_type' => null, // NAV adat, pl. HQ
            'postal_code' => 'alma',
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
        'Invalid postal_code.',
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
            'city' => null,
            'street_name' => 'Petőfi Sándor',
            'public_place_category' => StreetSuffix::STREET->value,
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Missing city.',
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
            'street_name' => null,
            'public_place_category' => StreetSuffix::STREET->value,
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Missing street_name.',
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
            'public_place_category' => null,
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Missing public_place_category.',
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
            'public_place_category' => 'alma',
            'number' => '34.',
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Invalid public_place_category.',
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
            'number' => null,
            'building' => '1.',
            'floor' => 'FSZ.',
            'door' => '12',
            'address' => null,
            'lane' => null,
        ],
        'Missing number.',
    ],

];
dataset('OrgAddressDataWrong', $dataSet);
