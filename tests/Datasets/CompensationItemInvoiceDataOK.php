<?php

declare(strict_types=1);

use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;

/**
 * partner_taxpayer_id in format: 12345678-1-12
 */
$dataSet = [
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => '2024-07-01',
            'fulfilment_date' => '2024-07-10',
            'late_interest_rate' => 0,
            'late_interest_amount' => 0,
            'invoice_amount' => 127300,
            'invoice_type' => InvoiceType::CLAIM->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'is_part_amount' => false,
            'is_disputed' => false,
            'is_compensed' => false,
        ],
        'Every data filled and correct.',
    ],
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => '2024-07-01',
            'fulfilment_date' => '2024-07-10',
            'late_interest_rate' => 0.3,
            'late_interest_amount' => 0,
            'invoice_amount' => 127300,
            'invoice_type' => InvoiceType::CLAIM->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'is_part_amount' => false,
            'is_disputed' => false,
            'is_compensed' => false,
        ],
        'Late interest rate is not a whole number.',
    ],
];
dataset('CompensationItemInvoiceDataOK', $dataSet);
