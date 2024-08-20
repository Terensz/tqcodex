<?php

namespace Domain\Project\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class CustomerReferralSystemController extends BaseContentController
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
     * List
     */
    public function index(Request $request)
    {
        return $this->renderContent($request, 'project.admin.customer-referral-system.index', 'CustomerReferralSystem', [
        ]);
    }
}
