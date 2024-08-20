<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\BasicFunctionality;

use Domain\Admin\Livewire\UserList;
use Domain\Admin\Models\User;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses()->group('admin-basic');

test('Livewire - Create and Search user', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());

    User::create([
        'firstname' => 'Din',
        'lastname' => 'Djarin',
        'email' => 'din.djarin.mando@gmail.com',
        'is_admin' => true,
        'email_verified_at' => now(),
        'password' => Hash::make('Test#88aa'),
        'remember_token' => Str::random(16),
    ]);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    Livewire::test(UserList::class)
        ->set('firstname', 'Din')
        ->set('lastname', 'Djarin')
        ->call('search')
        ->assertSee('din.djarin.mando@gmail.com');
});
