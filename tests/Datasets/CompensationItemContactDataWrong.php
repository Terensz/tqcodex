<?php

declare(strict_types=1);

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'contact_id' => null,
            'organization_id' => null,
        ],
        'Missing contact_id and organization_id.',
    ],
    [
        [
            'contact_id' => null,
            'organization_id' => 1, // Próba NYRT.
        ],
        'Missing contact_id.',
    ],
    [
        [
            'contact_id' => 1, // Not owned
            'organization_id' => 1,
        ],
        'Missing organization_id.',
    ],
    [
        [
            'contact_id' => 2,
            'organization_id' => null,
        ],
        'Missing organization_id.',
    ],
    [
        [
            'contact_id' => 2, // Terence
            'organization_id' => 1, // Próba NYRT.
        ],
        'organization_id does not belong to contact.',
    ],
    [
        [
            'contact_id' => 2, // Terence
            'organization_id' => 'alma', // invalid
        ],
        'Invalid organization_id.',
    ],
    [
        [
            'contact_id' => null,
            'organization_id' => null,
        ],
        'Missing contact_id and organization_id.',
    ],
    [
        [
            'contact_id' => '',
            'organization_id' => '',
        ],
        'Missing contact_id and organization_id #2.',
    ],
];
dataset('CompensationItemContactDataWrong', $dataSet);
