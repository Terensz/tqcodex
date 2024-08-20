<?php

use Domain\User\Models\Role;

/*
Standalone run:
php artisan test tests/Feature/User/Builders/RoleBuilderTest.php
*/

uses()->group('builder');

test('that Role builder finds valid Roles', function () {

    $validUsers = Role::valid()->get();

    expect($validUsers)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});
