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
php artisan test tests/Feature/Admin/Users/ContactEditBaseTest.php
*/

test('As Admin: viewing ContactEdit form, asserting all data.', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $admin);

    $contact = Contact::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    Livewire::test(\Domain\Admin\Livewire\ContactEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'contact' => $contact,
    ])
        ->assertSet('form.lastname', $contact->getContactProfile()->lastname)
        ->assertSet('form.firstname', $contact->getContactProfile()->firstname)
        ->assertSet('form.email', $contact->getContactProfile()->email)
        ->assertSet('form.phone', $contact->getContactProfile()->phone)
        ->assertSet('form.mobile', $contact->getContactProfile()->mobile);
});

test('As Admin: viewing ContactEdit form, changing email, phone and mobile, asserting results.', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $admin);

    $contact = Contact::factory()->createUntilNotTaken();

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    Livewire::test(\Domain\Admin\Livewire\ContactEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'contact' => $contact,
    ])
        ->assertSet('form.lastname', $contact->getContactProfile()->lastname)
        ->assertSet('form.firstname', $contact->getContactProfile()->firstname)
        ->assertSet('form.email', $contact->getContactProfile()->email)
        ->assertSet('form.phone', $contact->getContactProfile()->phone)
        ->assertSet('form.mobile', $contact->getContactProfile()->mobile)
        ->set('form.email', 'alma@almail.hu')
        ->set('form.phone', '+3615556667')
        ->set('form.mobile', '+36704445556')
        ->call('save');

    // Reloading screen
    Livewire::test(\Domain\Admin\Livewire\ContactEdit::class, [
        'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        'contact' => $contact,
    ])
        ->assertSet('form.lastname', $contact->getContactProfile()->lastname)
        ->assertSet('form.firstname', $contact->getContactProfile()->firstname)
        ->assertSet('form.email', 'alma@almail.hu')
        ->assertSet('form.phone', '+3615556667')
        ->assertSet('form.mobile', '+36704445556');
});
