<?php

namespace Domain\Project\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class AdminProjectController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PROJECT_ADMIN_INTERFACE;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * adminDashboard
     */
    public function dashboard(Request $request)
    {
        return $this->renderContent($request, 'admin.dashboard', __('shared.Dashboard'));
    }
}
