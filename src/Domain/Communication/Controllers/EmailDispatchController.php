<?php

namespace Domain\Communication\Controllers;

use Domain\Communication\Models\CommunicationDispatch;
use Domain\Communication\Models\CommunicationDispatchProcess;
use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class EmailDispatchController extends BaseContentController
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
    public function list(string $accessToken, ?CommunicationDispatchProcess $communicationDispatchProcess, Request $request)
    {
        return $this->renderContent($request, 'communication.email-dispatch.list', __('communication.EmailDispatchList'), [
            'communicationDispatchProcess' => $communicationDispatchProcess,
        ]);
    }

    /**
     * View
     */
    public function view(string $accessToken, CommunicationDispatch $communicationDispatch, Request $request)
    {
        return $this->renderContent($request, 'communication.email-dispatch.view', __('communication.EmailDispatchView'), [
            'communicationDispatch' => $communicationDispatch,
        ]);
    }
}
