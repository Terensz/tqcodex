<?php

// use Domain\Language\Services\Permissions;
// use Domain\User\Services\UserService;
// use Illuminate\Support\Facades\Route;

// $viewPermission = permission(Permissions::ADMIN_LANGUAGES)->view();
// $editPermission = permission(Permissions::ADMIN_LANGUAGES)->edit();
// $adminGuard = UserService::getGuardName(UserService::ROLE_TYPE_ADMIN);

// Route::get('language/list', [Domain\Language\Controllers\LanguageController::class, 'list'])
//     ->middleware(sprintf('permission:%s,%s', $viewPermission, $adminGuard))
//     ->name('admin.language.list');

// Route::match(['get', 'post'], 'language/edit/{model?}', [Domain\Language\Controllers\LanguageController::class, 'edit'])
//     ->middleware(sprintf('permission:%s,%s', $editPermission, $adminGuard))
//     ->name('admin.language.edit');
