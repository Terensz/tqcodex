<?php

use Domain\Admin\Models\User;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Livewire\Livewire;

/*
Standalone run:
php artisan test tests/Feature/Admin/Users/RoleListTest.php
*/

uses()->group('admin-basic');

test('As Admin: listing all Roles.', function () {
    $admin = User::factory()->createUntilNotTaken();

    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $admin);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($admin, UserService::GUARD_ADMIN);

    $test = Livewire::test(\Domain\Admin\Livewire\RoleList::class);

    $component = $test->instance();
    $paginator = $component->getPaginatorCollection();

    $showingResultsText = __('pagination.ShowingResults', [
        'fistElementIndex' => $paginator->firstItem(),
        'lastElementIndex' => $paginator->lastItem(),
        'totalElementsCount' => $paginator->total(),
    ]);

    if ($paginator->total() > $component->getPerPage()) {
        $test->assertSee($showingResultsText);
    } else {
        $test->assertDontSee($showingResultsText);
    }
});
