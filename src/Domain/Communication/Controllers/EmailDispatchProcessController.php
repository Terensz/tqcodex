<?php

namespace Domain\Communication\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class EmailDispatchProcessController extends BaseContentController
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
     * List
     */
    public function list(Request $request)
    {
        return $this->renderContent($request, 'communication.email-dispatch-process.list', __('communication.EmailDispatchProcessList'), [
        ]);
    }

    /**
     * New + Edit
     */
    public function edit($id = null) {}
}
