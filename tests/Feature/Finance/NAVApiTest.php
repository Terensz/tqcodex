<?php

use Domain\Finance\Services\Nav\NavQuery;

/*
Standalone run:
php artisan test tests/Feature/Customer/Project/NAVApiTest.php
*/

test('NAVApiTest', function () {
    /**
     * Írisz Office Zrt.
     */
    $taxpayerData = null;
    try {
        $taxpayerData = NavQuery::queryTaxpayer('25577710', 3);
        // dump($taxpayerData);
    } catch (Throwable $e) {
        dump($e);
    }

    expect($taxpayerData)->toBeObject();

    // dump($taxpayerData);

    expect(true)->toBeTrue();

})->group('ci');
