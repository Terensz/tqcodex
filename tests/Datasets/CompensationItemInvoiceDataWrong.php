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
            'invoice_id_for_compensation' => null,
            'invoice_internal_id' => null,
            'due_date' => null,
            'invoice_date' => null,
            'fulfilment_date' => null,
            'late_interest_rate' => null,
            'late_interest_amount' => null,
            'invoice_amount' => null,
            'invoice_type' => null,
            'payment_mode' => null,
            'currency' => null,
            'is_part_amount' => null,
            'is_disputed' => null,
            'is_compensed' => null,
        ],
        'Missing all data.',
    ],
    // due_date
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => 'alma',
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
        'due_date is not date format.',
    ],
    // invoice_date
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => 'alma',
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
        'invoice_date is not date format.',
    ],
    // fulfilment_date
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => '2024-07-01',
            'fulfilment_date' => 'alma',
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
        'fulfilment_date is not date format.',
    ],
    // late_interest_rate
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => '2024-07-01',
            'fulfilment_date' => '2024-07-10',
            'late_interest_rate' => 'alma',
            'late_interest_amount' => 0,
            'invoice_amount' => 127300,
            'invoice_type' => InvoiceType::CLAIM->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'is_part_amount' => false,
            'is_disputed' => false,
            'is_compensed' => false,
        ],
        'late_interest_rate has a wrong format.',
    ],
    // late_interest_amount
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => '2024-07-01',
            'fulfilment_date' => '2024-07-10',
            'late_interest_rate' => 0,
            'late_interest_amount' => 'alma',
            'invoice_amount' => 127300,
            'invoice_type' => InvoiceType::CLAIM->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'is_part_amount' => false,
            'is_disputed' => false,
            'is_compensed' => false,
        ],
        'late_interest_amount has a wrong format.',
    ],
    // late_interest_rate
    [
        [
            'invoice_id_for_compensation' => 'TEST-K-0000000123',
            'invoice_internal_id' => 'TEST-B-0000000123',
            'due_date' => '2024-09-01',
            'invoice_date' => '2024-07-01',
            'fulfilment_date' => '2024-07-10',
            'late_interest_rate' => 0,
            'late_interest_amount' => 14540.2,
            'invoice_amount' => 127300,
            'invoice_type' => InvoiceType::CLAIM->value,
            'payment_mode' => PaymentMode::TRANSFER->value,
            'currency' => Currency::HUF->value,
            'is_part_amount' => false,
            'is_disputed' => false,
            'is_compensed' => false,
        ],
        'Late interest amount is not a whole number.',
    ],
];
dataset('CompensationItemInvoiceDataWrong', $dataSet);
