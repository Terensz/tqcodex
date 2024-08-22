<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\Permission;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

class PermissionController extends BaseAdminController
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
        return $this->renderContent($request, 'user.permission.list', __('shared.ListOfItem', ['plural_item' => __('permission.Permissions')]), [
            'menu' => $this->getMenuData(),
            'admin' => $this->getAdminData(),
            'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/permission-edit',
        ]);
    }

    /**
     * New + Edit
     */
    public function edit(Permission $permission, Request $request)
    {
        return $this->renderContent($request, 'user.permission.edit', __('shared.EditItem', ['item' => __('permission.Permission')]), [
            'menu' => $this->getMenuData(),
            'permission' => $permission,
            'actionType' => $permission->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
