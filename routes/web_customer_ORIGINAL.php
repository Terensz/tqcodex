<?php

use Domain\Project\Services\PermissionSuffixService;
use Domain\User\Services\BasicPermissionSeederService;
use Domain\User\Services\PermissionService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// use Illuminate\Http\Request;

// RendringRouteContent::dispatch();

// App::setLocale('en');
Route::match(['get', 'post'], 'alma/nav/taxpayer-info/{taxpayerId}',
    // [\Domain\Finance\Controllers\NAVController::class, 'view']
    function ($taxpayerId) {
        dump($taxpayerId);
        exit;
    }
);

/**
 * Notice that we simply use the /customer prefix, without the access token.
 * UserService::getRoutePrefix(... returns only with /customer, UserService::getHome(... returns also with access token, if it's configured.
 */
Route::prefix(UserService::getRoutePrefix(UserService::ROLE_TYPE_CUSTOMER))->group(function () {
    /**
     * route: 'customer.password.request' (get)
     */
    Route::get('forgot-password', [\Domain\Customer\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('customer.password.request');

    /**
     * route: 'customer.password.email' (post)
     */
    Route::post('forgot-password', [\Domain\Customer\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('customer.password.email');

    /**
     * route: 'customer.password.reset' (get)
     */
    Route::get('reset-password/{token}', [\Domain\Customer\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('customer.password.reset');

    /**
     * route: 'customer.password.store' (post)
     */
    Route::match(['post'], 'reset-password', [\Domain\Customer\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('customer.password.store');

    /**
     * End of registration, "Thank you" text + resend verification email button.
     */

    /**
     * route: 'customer.verification.notice' (get)
     */
    Route::get('verify-email', \Domain\Customer\Controllers\Auth\EmailVerificationPromptController::class)
        ->name('customer.verification.notice');

    /**
     * route: 'customer.verification.send' (post)
     */
    Route::post('email/verification-notification', [\Domain\Customer\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('customer.verification.send');

    /**
     * route: 'customer.verification.verify' (get)
     */
    Route::get('verify-email/{id}/{hash}', \Domain\Customer\Controllers\Auth\VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('customer.verification.verify');
});

// Route::get('/invited-register/partnerEmail/{partnerEmail}/partnerName/{partnerName}/partnerContact/{partnerContact}', function (\Illuminate\Http\Request $request) {
//         if (! $request->hasValidSignature()) {
//             abort(401);
//         } else {
//             dump('helo te lo');exit;
//         }
//         // ...
//     })->name('customer.contact.invited-register');

// Route::match(['get'], 'register-successful', [\Domain\Customer\Controllers\CustomerRegistrationController::class, 'registerSuccessful'])
//     ->name('customer.contact.register-successful');

// Route::match(['post'], 'register', [\Domain\Customer\Controllers\CustomerRegistrationController::class, 'register'])
//     ->name('customer.contact.register.post');

/**
 * Customer logged out routes
 */
Route::middleware(['redirect.customer.if.authenticated'])->group(function () {
    Route::get('/'.UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_CUSTOMER].'/belepes', [\Domain\Customer\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->name('customer.login');

    Route::redirect('/'.UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_CUSTOMER].'/login', '/'.UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_CUSTOMER].'/belepes');

    Route::post('/'.UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_CUSTOMER].'/belepes', [\Domain\Customer\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
        ->name('customer.loginpost');
});

// Route::prefix('customer')->group(function () {

// });

/**
 * Requires auth, but works without access token.
 * Do not put routes here with view containing links with access token.
 */
$prefix = UserService::getRoutePrefix(UserService::ROLE_TYPE_CUSTOMER);
Route::prefix($prefix)->middleware(['auth.customer'])
    ->group(function () {
        /**
         * route: 'customer.email-change.create' (get)
         */
        Route::get('email-change/{token}', [\Domain\Customer\Controllers\EmailChangeController::class, 'create'])
            ->name('customer.email-change.create');

        Route::match(['post'], 'email-change', [\Domain\Customer\Controllers\EmailChangeController::class, 'store'])
            ->name('customer.email-change.store');
    });

/**
 * /customer/ routes
 * /Prefix/Domain/Controller/Method
 */
Route::middleware(['auth.customer', 'verify.access.token:'.UserService::ROLE_TYPE_CUSTOMER])
    ->prefix(UserService::getHomeRouteBase(UserService::ROLE_TYPE_CUSTOMER))
    ->group(function () {
        Route::get('dashboard', [\Domain\Customer\Controllers\CustomerController::class, 'dashboard'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.dashboard');

        Route::get('profile', [\Domain\Customer\Controllers\ProfileController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.profile.edit');

        Route::patch('profile', [\Domain\Customer\Controllers\ProfileController::class, 'update'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.profile.update');

        Route::delete('profile', [\Domain\Customer\Controllers\ProfileController::class, 'destroy'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_DELETE, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.profile.destroy');

        Route::post('profile/uploadImage', [\Domain\Customer\Controllers\ProfileController::class, 'uploadImage'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.profile.image.upload');

        Route::get('profile/showImage', [\Domain\Customer\Controllers\ProfileController::class, 'showImage'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.profile.image.show');

        Route::get('confirm-password', [\Domain\Customer\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.password.confirm');

        Route::post('confirm-password', [\Domain\Customer\Controllers\Auth\ConfirmablePasswordController::class, 'store'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.password.confirm.post');

        Route::put('password', [\Domain\Customer\Controllers\Auth\PasswordController::class, 'update'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.password.update');

        /**
         * Organizations
         */
        Route::get('customer/organization/list', [\Domain\Customer\Controllers\CustomerOrganizationController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.organization.list');

        Route::match(['get', 'post'], 'customer/organization/edit/{organization?}', [\Domain\Customer\Controllers\CustomerOrganizationController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.organization.edit');

        // Route::get('customer/org-address/list', [\Domain\Customer\Controllers\CustomerOrgAddressController::class, 'list'])
        //     ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
        //     ->name('customer.org-address.list');

        // Route::match(['get', 'post'], 'customer/org-address/edit/{orgAddress?}', [\Domain\Customer\Controllers\CustomerOrgAddressController::class, 'edit'])
        //     ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
        //     ->name('customer.org-address.edit');

        /**
         * Communication
         */
        Route::get('communication/dashboard', [\Domain\Communication\Controllers\CommunicationController::class, 'dashboard'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.communication.dashboard');

        Route::get('communication/email-dispatch-process/list', [\Domain\Communication\Controllers\EmailDispatchProcessController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.communication.email-dispatch-process.list');

        Route::get('communication/email-dispatch/list/{communicationDispatchProcess?}', [\Domain\Communication\Controllers\EmailDispatchController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.communication.email-dispatch.list');

        Route::get('communication/email-dispatch/view/{communicationDispatch}', [\Domain\Communication\Controllers\EmailDispatchController::class, 'view'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.communication.email-dispatch.view');

        /**
         * Project
         */
        Route::get('project/dashboard', [\Domain\Project\Controllers\CustomerProjectController::class, 'dashboard'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            // ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, BasicPermissionSeederService::SUFFIX_CUSTOMER_DASHBOARD).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.project.dashboard');

        // Project - Compensation items
        Route::get('project/compensation-item/list', [\Domain\Project\Controllers\CustomerCompensationItemController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            // ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, BasicPermissionSeederService::SUFFIX_CUSTOMER_DASHBOARD).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.project.compensation-item.list');

        Route::match(['get', 'post'], 'project/compensation-item/edit/{compensationItem?}', [\Domain\Project\Controllers\CustomerCompensationItemController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            // ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, BasicPermissionSeederService::SUFFIX_CUSTOMER_DASHBOARD).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.project.compensation-item.edit');

        Route::get('project/compensation-item/bulk-upload', [\Domain\Project\Controllers\CustomerCompensationItemController::class, 'bulkUpload'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ROLE_REGISTERED_CUSTOMER).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            // ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, BasicPermissionSeederService::SUFFIX_CUSTOMER_DASHBOARD).','.UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER))
            ->name('customer.project.compensation-item.bulk-upload');

        /**
         * redirects /customer/ (or /customer/{}) to /customer/dashboard
         */
        Route::redirect('', ''.UserService::getHome(UserService::ROLE_TYPE_CUSTOMER).'/dashboard');
    })->name('customer-interface');
