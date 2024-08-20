<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\BasicFunctionality;

use Domain\Admin\Livewire\RoleEdit;
use Domain\Admin\Livewire\RoleList;
use Domain\Admin\Models\User;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\Permission;
use Domain\User\Models\Role;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Support\Str;
use Livewire\Livewire;

/*
Standalone run:
php artisan test tests/Feature/Admin/Users/RoleMixedTest.php
*/

uses()->group('admin-basic');

test('Livewire - Roles list can be rendered', function () {
    $admin = User::factory()->createUntilNotTaken();
    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $admin);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    /** @phpstan-ignore-next-line  */
    $response = $this->get(route('admin.user.role.list', ['access_token' => $accessToken]));

    $response->assertStatus(200);
});

test('Livewire - Create, and Search created role', function () {
    $admin = User::factory()->createUntilNotTaken();

    UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $admin);

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());

    $randomString = Str::random();
    $testRoleName = 'TestRole'.$randomString;

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    /**
     * Creating model with the Livewire form
     */
    Livewire::test(RoleEdit::class, ['model' => new Role, 'actionType' => BaseEditComponent::ACTION_TYPE_NEW])
        ->set('form.name', $testRoleName)
        ->set('form.guard_name', UserService::GUARD_ADMIN)
        ->set('form.permission_data.admin_guard.RoleKeyAccountManager.VIEW')
        ->call('save');

    /**
     * Checking if we succeeded to make our new role
     */
    $testRolePermissionName = 'View_Role'.$testRoleName;
    $testRolePermission = Permission::where(['name' => $testRolePermissionName])->first();
    expect($testRolePermission)->toBeObject();

    /**
     * Checking Livewire list, if the created model is present in the list.
     */
    Livewire::test(RoleList::class)
        ->set('name', $testRoleName)
        ->call('search')
        ->assertSee($testRoleName);
});
