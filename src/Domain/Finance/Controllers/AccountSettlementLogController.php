<?php

namespace Domain\Finance\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class AccountSettlementLogController extends BaseContentController
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
    public function list(Request $request)
    {
        return $this->renderContent($request, 'finance.account-settlement-log.list', 'AccountSettlementLogList', [
        ]);
    }

    /**
     * View
     */
    public function view($id = null) {}
}
