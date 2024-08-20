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
php artisan test tests/Feature/Admin/Users/ContactEditRoleTest.php
*/

test('As Admin: adding role to customer.', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $admin);

    $contact = Contact::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    $roleActive_RegisteredCustomer = UserRoleService::customerHasRole('RegisteredCustomer', $contact);

    /** @phpstan-ignore-next-line  */
    $this->assertTrue($roleActive_RegisteredCustomer);

    Livewire::test(\Domain\Admin\Livewire\ContactEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'contact' => $contact,
    ])
        // Changing values
        ->set('form.email', 'alma@almail.hu')
        ->set('form.phone', '+3615556667')
        ->set('form.mobile', '+36704445556')
        ->set('form.role_data.RegisteredCustomer', false)
        // Submitting
        ->call('save');

    $roleActive_RegisteredCustomer = UserRoleService::customerHasRole('RegisteredCustomer', $contact);
    
    // Asserting if the role was successfully removed from this Contact.
    /** @phpstan-ignore-next-line  */
    $this->assertFalse($roleActive_RegisteredCustomer);
});
