<?php

declare(strict_types=1);

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => null,
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => null,
            'partner_contact' => 'Kiss János',
        ],
        'Missing name.',
    ],
    [
        [
            'partner_org_id' => 23456789223, // Invalid.
            'partner_location' => null,
            'partner_name' => 'Próba NYRT.',
            'partner_taxpayer_id' => null,
            'partner_email' => null,
            'partner_phone' => null,
            'partner_contact' => null,
        ],
        'Invalid partner_org_id.',
    ],
    [
        [
            'partner_org_id' => 'alma', // Invalid.
            'partner_location' => null,
            'partner_name' => 'Próba NYRT.',
            'partner_taxpayer_id' => null,
            'partner_email' => null,
            'partner_phone' => null,
            'partner_contact' => null,
        ],
        'Invalid partner_org_id #2.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma',
            'partner_phone' => null,
            'partner_contact' => 'Kiss János',
        ],
        'Invalid e-mail.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => 'alma',
            'partner_contact' => 'Kiss János',
        ],
        'Invalid phone.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '4234234324234234234234',
            'partner_contact' => 'Kiss János',
        ],
        'Invalid phone #2 (too long).',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '2323',
            'partner_contact' => 'Kiss János',
        ],
        'Invalid phone #3 (too short).',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '+36707',
            'partner_contact' => 'Kiss János',
        ],
        'Invalid phone #4 (too short #2).',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '13237912-2-19',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '+3670743454564567546',
            'partner_contact' => 'Kiss János',
        ],
        'Invalid phone #5 (too long #2).',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => null,
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '+36705150551',
            'partner_contact' => 'Kiss János',
        ],
        'Missing partner_taxpayer_id.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => 'AAAAA32344323234',
            'partner_email' => 'alma@almail.com',
            'partner_phone' => '+36705150551',
            'partner_contact' => 'Kiss János',
        ],
        'Invalid partner_taxpayer_id.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '5364733323-1-42',
            'partner_email' => null,
            'partner_phone' => '+36705150551',
            'partner_contact' => 'Kiss János',
        ],
        'Missing e-mail.',
    ],
    [
        [
            'partner_org_id' => null,
            'partner_location' => null,
            'partner_name' => 'Alma Kft.',
            'partner_taxpayer_id' => '5364733323-1-42',
            'partner_email' => '',
            'partner_phone' => '+36705150551',
            'partner_contact' => 'Kiss János',
        ],
        'Missing e-mail #2.',
    ],
    [
        [
            'partner_org_id' => 1,
            'partner_location' => null,
            'partner_name' => 'Alma Bt.',
            'partner_taxpayer_id' => null,
            'partner_email' => 'alma@almail.com',
            'partner_phone' => null,
            'partner_contact' => null,
        ],
        'Both partner_org_id and partner_name are set, but partner name differs to partner Organization->name, e-mail is also filled, but partner_contact is missing.',
    ],
];
dataset('CompensationItemPartnerDataWrong', $dataSet);
