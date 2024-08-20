<?php

use Domain\User\Models\Permission;

/*
Standalone run:
php artisan test tests/Feature/User/Builders/PermissionBuilderTest.php
*/

uses()->group('builder');

test('that Permission builder finds valid Permissions', function () {

    $validUsers = Permission::valid()->get();

    expect($validUsers)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});
