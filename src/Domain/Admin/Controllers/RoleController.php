<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\Role;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

class RoleController extends BaseAdminController
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
        return $this->renderContent($request, 'user.role.list', __('shared.ListOfItem', ['plural_item' => __('role.Roles')]), [
            'menu' => $this->getMenuData(),
            'user' => $this->getUserData(),
            'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/role-edit',
        ]);
    }

    /**
     * New + Edit
     */
    public function edit(string $accessToken, Role $role, Request $request)
    {
        return $this->renderContent($request, 'user.role.edit', __('shared.EditItem', ['item' => __('role.Role')]), [
            'menu' => $this->getMenuData(),
            'role' => $role,
            'actionType' => $role->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
