<?php

declare(strict_types=1);

use Database\Seeders\CommunicationSeeder;
use Domain\Communication\Models\CommunicationDispatch;
use Domain\Customer\Models\Contact;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\RouteHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\{get};
use function Pest\Laravel\{post};

$checkedRoutes = []; // Globális változó

/*
Standalone run:
php artisan test tests/Feature/Route/CustomerInterfaceGroupRouteTest.php
*/

it('gives back successful response for customer.dashboard page', function () use (&$checkedRoutes) {
    $routeName = 'customer.dashboard';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.password.confirm page', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.confirm';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.password.confirm.post page', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.confirm.post';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->post(route($routeName, ['access_token' => $accessToken]), ['email' => 'asdasdasd', 'password' => 'asdasdasd', 'ip' => 'dsaddas'])->assertFound();
});

it('gives back successful response for customer.password.update page', function () use (&$checkedRoutes) {
    $routeName = 'customer.password.update';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    $putParams = [
        // 'current_password' => 'TestCustomer9876543',
        'current_password' => $user->password,
        'password' => 'Alma12341234',
        'password_confirmation' => 'Alma12341234',
    ];
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->put(route($routeName, ['access_token' => $accessToken]), $putParams)->assertFound();
});

it('gives back successful response for customer.profile.edit', function () use (&$checkedRoutes) {
    $routeName = 'customer.profile.edit';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.profile.update', function () use (&$checkedRoutes) {
    $routeName = 'customer.profile.update';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.profile.destroy', function () use (&$checkedRoutes) {
    $routeName = 'customer.profile.destroy';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.profile.image.upload and customer.profile.image.show', function () use (&$checkedRoutes) {
    $routeName1 = 'customer.profile.image.upload';
    $checkedRoutes[] = $routeName1;

    $routeName2 = 'customer.profile.image.show';
    $checkedRoutes[] = $routeName2;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

    $filePath = base_path('tests/TestStorage/bananas1.jpeg');
    $file = new UploadedFile($filePath, 'bananas1.jpeg', 'image/jpeg', null, true);

    /**
     * Uploading image
     */
    post(route($routeName1, ['access_token' => $accessToken]), [
        'profile_image' => $file,
    ])->assertRedirect();

    /**
     * Viewing image
     */
    $this->get(route($routeName2, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.organization.list', function () use (&$checkedRoutes) {
    $routeName = 'customer.organization.list';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.organization.edit', function () use (&$checkedRoutes) {
    $routeName = 'customer.organization.edit';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

// test('gives back successful response for customer.org-address.list', function () use (&$checkedRoutes) {
//     $routeName = 'customer.org-address.list';
//     $checkedRoutes[] = $routeName;

//     $user = Contact::factory()->createUntilNotTaken();
//     AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
//     $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
//     //Act & Assert
//     $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
// });

// test('gives back successful response for customer.org-address.edit', function () use (&$checkedRoutes) {
//     $routeName = 'customer.org-address.edit';
//     $checkedRoutes[] = $routeName;

//     $user = Contact::factory()->createUntilNotTaken();
//     AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
//     $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
//     //Act & Assert
//     $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, [
//         'access_token' => $accessToken,
//     ]))->assertOk();
// });

it('gives back successful response for customer.communication.dashboard', function () use (&$checkedRoutes) {
    $routeName = 'customer.communication.dashboard';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.communication.email-dispatch-process.list', function () use (&$checkedRoutes) {
    $routeName = 'customer.communication.email-dispatch-process.list';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.communication.email-dispatch.list', function () use (&$checkedRoutes) {
    $routeName = 'customer.communication.email-dispatch.list';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.communication.email-dispatch.view', function () use (&$checkedRoutes) {
    $routeName = 'customer.communication.email-dispatch.view';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);

    /**
     * Now we must send an e-mail to check if it's view page is working or not.
     */
    $emailDispatcher = CommunicationSeeder::send($user, 1);
    $emailDispatchProcess = $emailDispatcher->communicationDispatchProcess;
    $emailDispatch = CommunicationDispatch::where(['communicationdispatchprocess_id' => $emailDispatchProcess->id])->first();

    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, [
        'access_token' => $accessToken,
        'communicationDispatch' => $emailDispatch->id,
    ]))->assertOk();
});

it('gives back successful response for customer.project.dashboard', function () use (&$checkedRoutes) {
    $routeName = 'customer.project.dashboard';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.project.compensation-item.list', function () use (&$checkedRoutes) {
    $routeName = 'customer.project.compensation-item.list';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.project.compensation-item.edit', function () use (&$checkedRoutes) {
    $routeName = 'customer.project.compensation-item.edit';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('gives back successful response for customer.project.compensation-item.bulk-upload', function () use (&$checkedRoutes) {
    $routeName = 'customer.project.compensation-item.bulk-upload';
    $checkedRoutes[] = $routeName;

    $user = Contact::factory()->createUntilNotTaken();
    AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, AccessTokenService::createAccessToken());
    $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER);
    //Act & Assert
    $this->actingAs($user, UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))->get(route($routeName, ['access_token' => $accessToken]))->assertOk();
});

it('checks if all tested routes are identified and belong to the CustomerInterfaceGroup.', function () use (&$checkedRoutes) {
    $routing = RouteHelper::getRouting();
    $unidentifiedRoutes = [];
    $routesNotBelongToCurrentGroup = [];
    foreach ($checkedRoutes as $checkedRoute) {
        $routeString = $checkedRoute.' ('.$routing[$checkedRoute]['contentGroup'].')';
        if (! isset($routing[$checkedRoute])) {
            $unidentifiedRoutes[] = $routeString;
        } else {
            if ($routing[$checkedRoute]['contentGroup'] != BaseContentController::CONTENT_GROUP_CUSTOMER_INTERFACE) {
                $routesNotBelongToCurrentGroup[] = $routeString;
            }

        }
    }

    $this->assertEquals([], $unidentifiedRoutes);
    $this->assertEquals([], $routesNotBelongToCurrentGroup);
});

it('checks tested routes cover all CustomerInterfaceGroup routes.', function () use (&$checkedRoutes) {
    $routing = RouteHelper::getRouting(BaseContentController::CONTENT_GROUP_CUSTOMER_INTERFACE);
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
