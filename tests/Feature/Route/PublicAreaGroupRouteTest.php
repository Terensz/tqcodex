<?php

declare(strict_types=1);

use Domain\Admin\Models\User;
use Domain\Customer\Models\Contact;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\RouteHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

use function Pest\Laravel\{get};
use function Pest\Laravel\{post};

$checkedRoutes = []; // Globális változó

/*
Standalone run:
php artisan test tests/Feature/Route/PublicAreaGroupRouteTest.php
*/

// Admin logged out routes

it('gives back successful response for admin.logout page', function () use (&$checkedRoutes) {
    $routeName = 'admin.logout';
    $checkedRoutes[] = $routeName;

    get(route($routeName))->assertRedirect();
});

it('gives back successful response for admin.login page', function () use (&$checkedRoutes) {
    $routeName = 'admin.login';
    $checkedRoutes[] = $routeName;

    get(route($routeName))->assertOk();
});

it('gives back successful response for admin.loginpost page', function () use (&$checkedRoutes) {
    $routeName = 'admin.loginpost';
    $checkedRoutes[] = $routeName;

    post(route($routeName))->assertFound();
});

it('gives back successful response for admin.password.request page', function () use (&$checkedRoutes) {
    $routeName = 'admin.password.request';
    $checkedRoutes[] = $routeName;

    $this->get(route($routeName))->assertOk();
});

it('gives back successful response for admin.password.email page', function () use (&$checkedRoutes) {
    $routeName = 'admin.password.email';
    $checkedRoutes[] = $routeName;

    $this->get(route($routeName))->assertOk();
});

it('gives back successful response for admin.password.reset page', function () use (&$checkedRoutes) {
    $routeName = 'admin.password.reset';
    $checkedRoutes[] = $routeName;

    $this->get(route($routeName, ['token' => '1234']))->assertOk();
});

it('gives back successful response for admin.password.store page', function () use (&$checkedRoutes) {
    $routeName = 'admin.password.store';
    $checkedRoutes[] = $routeName;

    $user = User::factory()->createUntilNotTaken();

    $this->post(route($routeName, ['token' => '1234', 'email' => $user->email]))->assertFound();
});

it('gives back successful response for admin.password.update page', function () use (&$checkedRoutes) {
    $routeName = 'admin.password.update';
    $checkedRoutes[] = $routeName;

    $this->put(route($routeName))->assertFound();
});

// Customer logged out routes

it('gives back successful response for customer.password.request page', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.request';
    $checkedRoutes[] = $routeName;

    get(route($routeName))->assertOk();
});

it('gives back successful response for customer.password.email page', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.email';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();

    post(route($routeName, ['email' => $user->getContactProfile()->email]))->assertRedirect();
});

it('gives back successful response for customer.password.reset page', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.reset';
    $checkedRoutes[] = $routeName;

    get(route($routeName, ['token' => '1234']))->assertOk();
});

it('gives back successful response for customer.password.store post', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.store';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();

    post(route($routeName, ['token' => '1234', 'email' => $user->getContactProfile()->email]))->assertFound();
});

it('gives back successful response for customer.email-change.create', function () use (&$checkedRoutes) {
    $routeName = 'customer.email-change.create';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
        ->get(route($routeName, ['token' => 'sadasdasasdasdasd']))->assertOk();
});

it('gives back successful response for customer.email-change.store', function () use (&$checkedRoutes) {
    $routeName = 'customer.email-change.store';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();

    post(route($routeName, [
        'token' => '1234',
        'old_email' => $user->getContactProfile()->email,
        'email' => $user->getContactProfile()->email,
    ]))->assertRedirect();
});

it('gives back successful response for customer.verification.notice', function () use (&$checkedRoutes) {
    $routeName = 'customer.verification.notice';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    $user->email_verified_at = null;
    $user->save();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.verification.verify', function () use (&$checkedRoutes) {
    $routeName = 'customer.verification.verify';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    $user->email_verified_at = null;
    $user->save();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['id' => 2, 'hash' => 'sdaddfsdfsd']))->assertForbidden();
});

it('gives back successful response for customer.verification.send', function () use (&$checkedRoutes) {
    $routeName = 'customer.verification.send';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    $user->email_verified_at = null;
    $user->save();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->post(route($routeName))->assertFound();
});

it('gives back successful response for customer.login page', function () use (&$checkedRoutes) {
    $routeName = 'customer.login';
    $checkedRoutes[] = $routeName;

    get(route($routeName))->assertOk();
});

it('gives back successful response for customer.loginpost page', function () use (&$checkedRoutes) {
    $routeName = 'customer.loginpost';
    $checkedRoutes[] = $routeName;

    post(route($routeName))->assertFound();
});

it('gives back successful response for customer.logout page', function () use (&$checkedRoutes) {
    $routeName = 'customer.logout';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName))->assertFound();
});

it('gives back successful response for admin.email-change.create page', function () use (&$checkedRoutes) {
    $routeName = 'admin.email-change.create';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['token' => 'sadasdreewr423423423']))->assertRedirect();
});

it('gives back successful response for admin.email-change.store page', function () use (&$checkedRoutes) {
    $routeName = 'admin.email-change.store';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName))->assertFound();
});

it('gives back successful response for customer.contact.register page', function () use (&$checkedRoutes) {
    $routeName = 'customer.contact.register';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName))->assertOk();
});

it('gives back successful response for customer.contact.invited-register page', function () use (&$checkedRoutes) {
    $routeName = 'customer.contact.invited-register';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, [
        'partnerEmail' => 'alma@almail.com',
        'partnerName' => 'Alma és Társa Nyrt.',
        'partnerContact' => 'Alma László',
    ]))->assertStatus(401);
});

it('gives back successful response for logout', function () use (&$checkedRoutes) {
    $routeName = 'logout';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    $user->email_verified_at = null;
    $user->save();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName))->assertRedirect();
});

it('gives back successful response for admin.language.list', function () use (&$checkedRoutes) {
    $routeName = 'admin.language.list';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    $user->email_verified_at = null;
    $user->save();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName))->assertRedirect();
});

it('gives back successful response for admin.language.edit', function () use (&$checkedRoutes) {
    $routeName = 'admin.language.edit';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    $user->email_verified_at = null;
    $user->save();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName))->assertRedirect();
});

it('checks if all tested routes are identified and belong to the PublicAreaGroup.', function () use (&$checkedRoutes) {
    $routing = RouteHelper::getRouting();
    $unidentifiedRoutes = [];
    $routesNotBelongToCurrentGroup = [];
    foreach ($checkedRoutes as $checkedRoute) {
        $routeString = $checkedRoute.' ('.$routing[$checkedRoute]['contentGroup'].')';
        if (! isset($routing[$checkedRoute])) {
            $unidentifiedRoutes[] = $routeString;
        } else {
            if ($routing[$checkedRoute]['contentGroup'] != BaseContentController::CONTENT_GROUP_PUBLIC_AREA) {
                $routesNotBelongToCurrentGroup[] = $routeString;
            }
        }
    }

    $this->assertEquals([], $unidentifiedRoutes);
    $this->assertEquals([], $routesNotBelongToCurrentGroup);
});

it('checks tested routes cover all PublicAreaGroup routes.', function () use (&$checkedRoutes) {
    $routing = RouteHelper::getRouting(BaseContentController::CONTENT_GROUP_PUBLIC_AREA);
    $routesNotFound = [];
    foreach ($routing as $routeName => $routeParams) {
        $routeString = $routeName.' ('.$routeParams['contentGroup'].')';
        if ($routeParams['name']) {
            if (! in_array($routeParams['name'], $checkedRoutes)) {
                $routesNotFound[] = $routeString;
            }
        }
    }

    $this->assertEquals([], $routesNotFound);
});
