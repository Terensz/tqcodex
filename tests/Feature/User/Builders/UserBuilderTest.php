<?php

use Domain\Admin\Models\User;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;

/*
Standalone run:
php artisan test tests/Feature/User/Builders/UserBuilderTest.php
*/

uses()->group('builder');

test('find Holbok Istvan user by email address', function () {

    $user1 = User::where('email', 'terencecleric@gmail.com')->first();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $user1);
    $this->actingAs($user1, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    $user2 = User::listableByAdmin()->where('email', 'admin@trianity.dev')->first();

    $this->assertEquals($user2->firstname, 'István');
});

test('that User builder finds valid Users', function () {

    $validUsers = User::valid()->get();

    expect($validUsers)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    expect(true)->toBeTrue();
});
