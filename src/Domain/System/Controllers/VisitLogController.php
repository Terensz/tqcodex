<?php

namespace Domain\System\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class VisitLogController extends BaseContentController
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
        return $this->renderContent($request, 'system.visit-log.list', __('shared.ListOfItem', ['plural_item' => __('system.VisitLogs')]), [
        ]);
    }

    /**
     * View
     */
    public function view($id = null) {}
}
