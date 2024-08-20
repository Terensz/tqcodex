<?php

namespace Domain\Customer\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class ContactDataController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE;
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
        return $this->renderContent($request, 'admin.customer.contact-data.list', 'customer.CustomerContactDataList', [
        ]);
    }

    /**
     * New + Edit
     */
    public function edit($id = null) {}
}
