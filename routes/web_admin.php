<?php

use Domain\Admin\Controllers\Auth\AuthenticatedSessionController;
use Domain\Admin\Controllers\Auth\NewPasswordController;
use Domain\Admin\Controllers\Auth\PasswordController;
use Domain\Admin\Controllers\Auth\PasswordResetLinkController;
use Domain\Admin\Controllers\EmailChangeController;
use Domain\Admin\Controllers\ProfileController;
use Domain\Language\Services\Permissions;
use Domain\Project\Controllers\AdminProjectController;
use Domain\Project\Services\PermissionSuffixService;
use Domain\User\Services\PermissionService;
use Domain\User\Services\UserService;
use Illuminate\Support\Facades\Route;

Route::middleware([])->group(function () {
    $prefix = UserService::getRoutePrefix(UserService::ROLE_TYPE_ADMIN);
    /**
     * Admin logged out routes
     */
    Route::middleware(['redirect.admin.if.authenticated'])->prefix($prefix)->group(function () {
        Route::get('/belepes', [AuthenticatedSessionController::class, 'login'])
            ->name('admin.login');

        // Route::redirect('/login', '/belepes');
        Route::redirect('/'.UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_ADMIN].'/login', '/'.UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_ADMIN].'/belepes');

        Route::post('/belepes', [AuthenticatedSessionController::class, 'auth'])
            ->name('admin.loginpost');
    });

    /**
     * Notice that we simply use the /admin prefix, without the access token.
     * UserService::getRoutePrefix(... returns only with /admin, UserService::getHome(... returns also with access token, if it's configured.
     */
    Route::prefix($prefix)->group(function () {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('admin.password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('admin.password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('admin.password.reset');

        Route::match(['post'], 'reset-password', [NewPasswordController::class, 'store'])
            ->name('admin.password.store');

        Route::put('password', [PasswordController::class, 'update'])
            ->name('admin.password.update');

        /**
         * End of registration, "Thank you" text + resend verification email button.
         * Now these routes are disabled.
         */

        // Route::get('verify-email', EmailVerificationPromptController::class)
        //     ->name('admin.verification.notice');

        // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        //     ->middleware(['signed', 'throttle:6,1'])
        //     ->name('admin.verification.verify');

        // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        //     ->middleware('throttle:6,1')
        //     ->name('admin.verification.send');
    });
});

/**
 * Requires auth, but works without access token.
 * Do not put routes here with view containing links with access token.
 */
$prefix = UserService::getRoutePrefix(UserService::ROLE_TYPE_ADMIN);
Route::prefix($prefix)->middleware(['auth.admin'])
    ->group(function () {
        /**
         * route: 'admin.email-change.create' (get)
         */
        Route::get('email-change/{token}', [EmailChangeController::class, 'create'])
            ->name('admin.email-change.create');

        Route::match(['post'], 'email-change', [EmailChangeController::class, 'store'])
            ->name('admin.email-change.store');

        $viewPermission = permission(Permissions::ADMIN_LANGUAGES)->view();
        $editPermission = permission(Permissions::ADMIN_LANGUAGES)->edit();
        $adminGuard = UserService::getGuardName(UserService::ROLE_TYPE_ADMIN);

        Route::get('language/list', [Domain\Language\Controllers\LanguageController::class, 'list'])
            ->middleware(sprintf('permission:%s,%s', $viewPermission, $adminGuard))
            ->name('admin.language.list');

        Route::match(['get', 'post'], 'language/edit/{model?}', [Domain\Language\Controllers\LanguageController::class, 'edit'])
            ->middleware(sprintf('permission:%s,%s', $editPermission, $adminGuard))
            ->name('admin.language.edit');
    });

/**
 * /admin/ routes
 * /Prefix/Domain/Controller/Method
 */
Route::middleware(['auth.admin', 'verify.access.token:'.UserService::ROLE_TYPE_ADMIN])
    ->prefix(UserService::getHomeRouteBase(UserService::ROLE_TYPE_ADMIN))
    ->group(function () {
        Route::get('dashboard', [\Domain\Admin\Controllers\AdminInterfaceController::class, 'dashboard'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_DASHBOARD).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_PROFILE).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.profile.edit');

        Route::patch('profile', [ProfileController::class, 'update'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ADMIN_PROFILE).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.profile.update');

        Route::delete('profile', [ProfileController::class, 'destroy'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_DELETE, PermissionSuffixService::SUFFIX_ADMIN_PROFILE).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.profile.destroy');

        // Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        //     ->name('admin.password.confirm');

        // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
        //     ->name('admin.password.confirm.post');

        /**
         * redirects /admin/ (or /admin/{}) to /admin/dashboard
         */
        // Route::redirect('', ''.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/dashboard');

        /**
         * UserList
         */
        Route::get('admin/user/list', [Domain\Admin\Controllers\AdminController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_ADMINS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.admin.user.list');

        Route::match(['get', 'post'], 'admin/user/edit/{user?}', [Domain\Admin\Controllers\AdminController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ADMIN_ADMINS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.admin.user.edit');

        /**
         * ContactList
         */
        Route::get('admin/contact/list', [Domain\Admin\Controllers\ContactController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.admin.contact.list');

        Route::match(['get', 'post'], 'admin/contact/edit/{contact?}', [Domain\Admin\Controllers\ContactController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.admin.contact.edit');

        Route::match(['get', 'post'], 'admin/contact/impersonate/{contact?}', [Domain\Admin\Controllers\ContactController::class, 'impersonate'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.admin.contact.impersonate');

        /**
         * PermissionList
         */
        Route::get('user/permission/list', [Domain\Admin\Controllers\PermissionController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_PERMISSIONS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.user.permission.list');

        Route::match(['get', 'post'], 'user/permission/edit/{permission?}', [Domain\Admin\Controllers\PermissionController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ADMIN_PERMISSIONS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.user.permission.edit');

        /**
         * RoleList
         */
        Route::get('user/role/list', [Domain\Admin\Controllers\RoleController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_ROLES).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.user.role.list');

        Route::match(['get', 'post'], 'user/role/edit/{role?}', [Domain\Admin\Controllers\RoleController::class, 'edit'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_EDIT, PermissionSuffixService::SUFFIX_ADMIN_ROLES).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.user.role.edit');

        /**
         * AssignPermissionToRoleList
         */
        // Route::get('user/role-has-permission/list', [Domain\Admin\Controllers\RoleHasPermissionController::class, 'list'])->name('admin.user.role-has-permission.list');

        // Route::match(['get', 'post'], 'user/role-has-permission/edit/{role?}/{permission?}', [Domain\Admin\Controllers\RoleHasPermissionController::class, 'edit'])->name('admin.user.role-has-permission.edit');

        /**
         * UsersSessionLogList
         */
        // Route::get('user/session-log/list', [Domain\Admin\Controllers\SessionLogController::class, 'list'])
        //     ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_ADMINS_ACTIVITY_LOG).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        //     ->name('admin.user.session-log.list');

        // Route::post('user/session-log/view/{id?}', [Domain\User\Controllers\SessionLogController::class, 'edit'])->name('admin.user-session-log.view');

        /**
         * UsersActivityLogList
         */
        Route::get('user/user-activity-log/list', [Domain\Admin\Controllers\AdminActivityLogController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_ADMINS_ACTIVITY_LOG).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.user.user-activity-log.list');

        // Route::post('user/user-activity-log/view/{id?}', [Domain\User\Controllers\UserActivityLogController::class, 'edit'])->name('admin.user.user-activity-log.view');

        /**
         * CustomersActivityLogList
         */
        Route::get('customer/contact-activity-log/list', [Domain\Customer\Controllers\ContactActivityLogController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_CUSTOMERS_ACTIVITY_LOG).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.customer.contact-activity-log.list');

        // Route::post('customer/contact-activity-log/view/{id?}', [Domain\Customer\Controllers\ContactActivityLogController::class, 'edit'])->name('admin.customer.contact-activity-log.view');

        /**
         * SystemErrorLogList
         */
        Route::get('system/error-log/list', [Domain\System\Controllers\ErrorLogController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_SYSTEM_ERROR_LOG).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.system.error-log.list');

        // Route::post('system/error-log/view/{id?}', [Domain\System\Controllers\ErrorLogController::class, 'view'])
        //     ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_SYSTEM_ERROR_LOG).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        //     ->name('admin.system.error-log.view');

        /**
         * VisitLogList
         */
        Route::get('system/visit-log/list', [Domain\System\Controllers\VisitLogController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_VISIT_LOG).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.system.visit-log.list');

        /**
         * SettingsList
         */
        Route::get('system/setting/list', [Domain\System\Controllers\SettingController::class, 'list'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_SYSTEM_SETTINGS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.system.setting.list');

        // Route::post('system/setting/view/{id?}', [Domain\System\Controllers\SettingController::class, 'view'])
        //     ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_SYSTEM_SETTINGS).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
        //     ->name('admin.system.setting.view');

        /**
         * Project dashboard
         */
        Route::get('project/dashboard', [AdminProjectController::class, 'dashboard'])
            ->middleware('permission:'.PermissionService::createPermissionName(PermissionService::PREFIX_VIEW, PermissionSuffixService::SUFFIX_ADMIN_DASHBOARD).','.UserService::getGuardName(UserService::ROLE_TYPE_ADMIN))
            ->name('admin.project.dashboard');
    });
