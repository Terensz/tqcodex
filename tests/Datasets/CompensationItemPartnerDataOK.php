<?php

declare(strict_types=1);

use Domain\Customer\Enums\Location;

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'partner_org_id' => 3, // Nagyanyáink Sütödéje Kft.
            'partner_location' => null,
            'partner_name' => 'Nagyanyáink Sütödéje Kft.',
            'partner_taxpayer_id' => '14675302-2-03',
            'partner_email' => null,
            'partner_phone' => null,
            'partner_contact' => null,
        ],
        'Valid partner_org_id, partner name equals to partner Organization->name, no other partner data are filled.',
    ],
    [
        [
            'partner_org_id' => 6, // GYŐRI ÉDENKERT Kft.
            'partner_location' => null,
            'partner_name' => 'GYŐRI ÉDENKERT Kft.',
            'partner_taxpayer_id' => null,
            'partner_email' => null,
            'partner_phone' => null,
            'partner_contact' => null,
        ],
        'Valid partner_org_id, partner name equals to partner Organization->name, no other partner data are filled.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => Location::HU->value,
            'partner_name' => 'AMBROZIA FOOD KFT.',
            'partner_taxpayer_id' => '13352479-2-13',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => null,
            'partner_contact' => 'Kiss János',
        ],
        'No org_id, missing phone, other partner data are valid.',
    ],
    [
        [
            'partner_org_id' => 1,
            'partner_location' => null,
            'partner_name' => 'AMBROZIA FOOD KFT.',
            'partner_taxpayer_id' => '13352479-2-13',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => null,
            'partner_contact' => 'Kiss János',
        ],
        'Both partner_org_id and partner_name are set, but partner name differs to partner Organization->name, all other partner data are filled.',
    ],
    [
        [
            'partner_org_id' => 6,
            'partner_location' => null,
            'partner_name' => 'GYŐRI ÉDENKERT Kft.',
            'partner_taxpayer_id' => null,
            'partner_email' => null,
            'partner_phone' => null,
            'partner_contact' => null,
        ],
        'Both partner_org_id and partner_name are set, but partner name differs to partner Organization->name, no other partner data are filled.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => Location::HU->value,
            'partner_name' => 'AMBROZIA FOOD KFT.',
            'partner_taxpayer_id' => '13352479-2-13',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '+36705150551',
            'partner_contact' => 'Kiss János',
        ],
        'No org_id, phone is filled, other partner data are valid.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => Location::HU->value,
            'partner_name' => 'AMBROZIA FOOD KFT.',
            'partner_taxpayer_id' => '13352479-2-13',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '705150551',
            'partner_contact' => 'Kiss János',
        ],
        'Phone does not contain + and country pre, but still works.',
    ],
];
dataset('CompensationItemPartnerDataOK', $dataSet);
