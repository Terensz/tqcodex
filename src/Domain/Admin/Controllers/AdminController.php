<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

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
     * dashboard
     */
    public function dashboard(Request $request)
    {
        return $this->renderContent($request, 'admin.dashboard', __('shared.Dashboard'));
        // return $this->renderContent('admin.contents.user.list', 'UserList', [
        //     'menu' => $this->getMenuData(),
        //     'user' => $this->getUserData(),
        //     'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/user-edit'
        // ]);
    }
}
