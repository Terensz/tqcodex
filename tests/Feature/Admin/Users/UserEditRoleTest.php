<?php

use Domain\Admin\Models\User;
use Domain\Customer\Models\Contact;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Livewire\Livewire;

/*
Standalone run:
php artisan test tests/Feature/Admin/Users/UserEditRoleTest.php
*/

uses()->group('admin-basic');

test('As Admin: adding role to another admin.', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $admin);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    $user = User::factory()->createUntilNotTaken();

    $roleActive_FinancialAccountingManager = UserRoleService::adminHasRole('FinancialAccountingManager', $user);

    /** @phpstan-ignore-next-line  */
    $this->assertFalse($roleActive_FinancialAccountingManager);

    Livewire::test(\Domain\Admin\Livewire\UserEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'user' => $user,
    ])
        // Changing values
        ->set('form.email', 'alma.admin.2@almail.hu')
        ->set('form.role_data.FinancialAccountingManager', true)
        // Submitting
        ->call('save');

    $user->refresh();

    $roleActive_FinancialAccountingManager = UserRoleService::adminHasRole('FinancialAccountingManager', $user);
    
    // Asserting if the role was successfully assigned to this Contact.
    /** @phpstan-ignore-next-line  */
    $this->assertTrue($roleActive_FinancialAccountingManager);
});
