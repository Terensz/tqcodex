<?php

// use Illuminate\Support\Facades\Route as RouteHelper;

use Domain\Admin\Models\User;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\RouteHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Routing\Route;

use function Pest\Laravel\{get};

/*
Standalone run:
php artisan test tests/Feature/Route/AdminInterfaceGroupRouteTest.php
*/

$checkedRoutes = []; // Gloal variable

it('gives back successful response for admin.dashboard page', function () use (&$checkedRoutes) {

    $routeName = 'admin.dashboard';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.profile.edit page', function () use (&$checkedRoutes) {

    $routeName = 'admin.profile.edit';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.profile.update page', function () use (&$checkedRoutes) {

    $routeName = 'admin.profile.update';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->patch(route($routeName, ['access_token' => $accessToken]), [
        'lastname' => 'Test',
        'firstname' => 'User',
        'email' => 'alma@almail.com',
    ])->assertRedirect();
});

it('gives back successful response for admin.profile.destroy page', function () use (&$checkedRoutes) {

    $routeName = 'admin.profile.destroy';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->delete(route($routeName, ['access_token' => $accessToken]))->assertRedirect();
});

it('gives back successful response for admin.admin.user.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.admin.user.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.admin.user.edit page', function () use (&$checkedRoutes) {

    $routeName = 'admin.admin.user.edit';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.admin.contact.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.admin.contact.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.admin.contact.edit page', function () use (&$checkedRoutes) {

    $routeName = 'admin.admin.contact.edit';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.admin.contact.impersonate page', function () use (&$checkedRoutes) {
    $routeName = 'admin.admin.contact.impersonate';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertRedirect();
});

it('gives back successful response for admin.user.permission.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.user.permission.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.user.permission.edit page', function () use (&$checkedRoutes) {

    $routeName = 'admin.user.permission.edit';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.user.role.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.user.role.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.user.role.edit page', function () use (&$checkedRoutes) {

    $routeName = 'admin.user.role.edit';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.system.error-log.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.system.error-log.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.system.visit-log.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.system.visit-log.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.user.user-activity-log.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.user.user-activity-log.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.customer.contact-activity-log.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.customer.contact-activity-log.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.system.setting.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.system.setting.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.project.dashboard page', function () use (&$checkedRoutes) {

    $routeName = 'admin.project.dashboard';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.finance.cashflow-data.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.finance.cashflow-data.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.finance.cashflow-data.view page', function () use (&$checkedRoutes) {

    $routeName = 'admin.finance.cashflow-data.view';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertRedirect();
});

it('gives back successful response for admin.finance.account-settlement-log.list page', function () use (&$checkedRoutes) {

    $routeName = 'admin.finance.account-settlement-log.list';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.finance.account-settlement-log.view page', function () use (&$checkedRoutes) {

    $routeName = 'admin.finance.account-settlement-log.view';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertRedirect();
});

it('gives back successful response for admin.project.customer-referral-system.index page', function () use (&$checkedRoutes) {

    $routeName = 'admin.project.customer-referral-system.index';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for admin.project.service-prices.index page', function () use (&$checkedRoutes) {

    $routeName = 'admin.project.service-prices.index';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_ADMIN, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);
    UserRoleService::syncRolesToAdmin([RoleService::ROLE_SUPER_ADMIN], $user);

    /** @phpstan-ignore-next-line  */
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

    /** @phpstan-ignore-next-line  */
    $this->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('checks if all tested routes are identified and belong to the AdminInterfaceGroup.', function () use (&$checkedRoutes) {
    $routing = RouteHelper::getRouting();
    $unidentifiedRoutes = [];
    $routesNotBelongToCurrentGroup = [];
    foreach ($checkedRoutes as $checkedRoute) {
        $routeString = $checkedRoute.' ('.$routing[$checkedRoute]['contentGroup'].')';
        if (! isset($routing[$checkedRoute])) {
            $unidentifiedRoutes[] = $routeString;
        } else {
            if ($routing[$checkedRoute]['contentGroup'] != BaseContentController::CONTENT_GROUP_ADMIN_INTERFACE) {
                $routesNotBelongToCurrentGroup[] = $routeString;
            }

        }
    }

    /** @phpstan-ignore-next-line  */
    $this->assertEquals([], $unidentifiedRoutes);

    /** @phpstan-ignore-next-line  */
    $this->assertEquals([], $routesNotBelongToCurrentGroup);
});

it('checks tested routes cover all AdminInterfaceGroup routes.', function () use (&$checkedRoutes) {
    $routing = RouteHelper::getRouting(BaseContentController::CONTENT_GROUP_ADMIN_INTERFACE);
    $routesNotFound = [];
    foreach ($routing as $routeName => $routeParams) {
        $routeString = $routeName.' ('.$routeParams['contentGroup'].')';
        if ($routeParams['name']) {
            if (! in_array($routeParams['name'], $checkedRoutes)) {
                $routesNotFound[] = $routeString;
            }
        }
    }

    /** @phpstan-ignore-next-line  */
    $this->assertEquals([], $routesNotFound);
});
