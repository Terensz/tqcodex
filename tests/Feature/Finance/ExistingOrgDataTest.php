<?php

use Domain\Customer\Services\OrganizationSeederService;

/*
Standalone run:
php artisan test tests/Feature/Finance/ExistingOrgDataTest.php
*/

test('Testing OrganizationSeederService::getExistingOrgData($index) method.', function () {

    $orgData = OrganizationSeederService::getExistingOrgData(41);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals('CBA-Grand Gourmet Kft.', $orgData ? $orgData->shortName : null);
});
