<?php

use Domain\Admin\Models\User;
use Domain\Customer\Models\Contact;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

/*
Standalone run:
php artisan test tests/Feature/Admin/Users/ContactEditPasswordChangeTest.php
*/

test('As Admin: changing Contacts password. As Contact: logging in.', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $admin);

    $contact = Contact::factory()->createUntilNotTaken();

    $newPassword = 'Rg6B3MLp4Gg45m1V2';

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
        ->set('form.technicalPassword', $newPassword)
        ->call('save');

    // Verify that the contact's password was updated
    $contact->refresh();

    /** @phpstan-ignore-next-line  */
    $this->assertTrue(Hash::check($newPassword, $contact->password));

    // $this->get(route('customer.login'))
    //     ->assertStatus(200);

    // $response = $this->post(route('customer.loginpost'), [
    //     'email' => $contact->getContactProfile()->email,
    //     'password' => $newPassword,
    // ]);

    // Check if login was successful by asserting redirect to intended page or dashboard
    // $route = UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER);
    // $response->assertRedirect($route);

    // Check if the contact is authenticated
    // $this->assertAuthenticatedAs($contact, UserService::GUARD_CUSTOMER);
});
