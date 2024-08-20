<?php

namespace Domain\Project\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class CustomerProjectController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PROJECT_CUSTOMER_INTERFACE;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * customer's project dashboard
     */
    public function dashboard(Request $request)
    {
        return $this->renderContent($request, 'customer.dashboard', __('shared.Dashboard'));
    }
}
