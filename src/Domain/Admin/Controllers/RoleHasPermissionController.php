<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\Role;
use Domain\User\Models\RoleHasPermission;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

class RoleHasPermissionController extends BaseAdminController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_BASIC_ADMIN_INTERFACE;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * List
     */
    public function list(Request $request)
    {
        return $this->renderContent($request, 'user.role-has-permission.list', 'PermissionsOfRolesList', [
            'menu' => $this->getMenuData(),
            'admin' => $this->getAdminData(),
            // 'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/role-edit',
        ]);
    }

    /**
     * New + Edit
     */
    public function edit(RoleHasPermission $roleHasPermission, Request $request)
    {
        return $this->renderContent($request, 'user.role-has-permission.edit', 'EditPermissionOfRole', [
            'menu' => $this->getMenuData(),
            'roleHasPermission' => $roleHasPermission,
            'actionType' => $roleHasPermission->exists ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
