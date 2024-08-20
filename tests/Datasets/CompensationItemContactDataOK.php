<?php

declare(strict_types=1);

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'contact_id' => 2, // Terence
            'organization_id' => 4, // TRIEX KFT
        ],
        'organization_id does belong to contact.',
    ],
    [
        [
            'contact_id' => 2, // Terence
            'organization_id' => 5, // SPAR MAGYARORSZÁG
        ],
        'organization_id does belong to contact #2.',
    ],
];
dataset('CompensationItemContactDataOK', $dataSet);
