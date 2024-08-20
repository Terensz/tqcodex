<?php

namespace Domain\Communication\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class CommunicationController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_COMMUNICATION_CUSTOMER_INTERFACE;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * dashboard
     */
    public function dashboard(Request $request)
    {
        // dump('dashboard');exit;
        return $this->renderContent($request, 'communication.dashboard', __('shared.Dashboard'));
        // return $this->renderContent('admin.contents.user.list', 'UserList', [
        //     'menu' => $this->getMenuData(),
        //     'user' => $this->getUserData(),
        //     'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/user-edit'
        // ]);
    }
}
