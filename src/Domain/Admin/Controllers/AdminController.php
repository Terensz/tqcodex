<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Admin\Models\Admin;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

/**
 * CRUD controller for User
 */
class AdminController extends BaseAdminController
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
    public function __construct()
    {
        // $this->middleware(['validate.customer', 'redirect.non.admin']);
    }

    /**
     * List
     */
    public function list(Request $request)
    {
        return $this->renderContent($request, 'admin.admin.list', __('shared.ListOfItem', ['plural_item' => __('admin.Admins')]), [
            'menu' => $this->getMenuData(),
            'admin' => $this->getAdminData(),
            // 'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/user-edit',
        ]);
    }

    /**
     * New + Edit
     */
    public function edit(string $accessToken, Admin $admin, Request $request)
    {
        return $this->renderContent($request, 'admin.admin.edit', __('shared.EditItem', ['item' => __('admin.Admin')]), [
            'menu' => $this->getMenuData(),
            'admin' => $admin,
            'actionType' => $admin->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
        // return $this->renderAjaxResponse('admin.user.edit', [
        //     // 'object' => $object,
        // ], [
        //     'label' => __('admin.'.($user->id ? 'Edit' : 'New').'User'),
        //     'entityObject' => $user
        // ]);
    }
}
