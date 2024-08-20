<?php

declare(strict_types=1);

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'taxpayer_id' => '12311231-1-41',
        ],
        'Every data filled and correct.',
    ],
];
dataset('HUTaxIdDataWrong', $dataSet);
