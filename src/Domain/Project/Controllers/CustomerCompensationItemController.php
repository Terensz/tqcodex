<?php

namespace Domain\Project\Controllers;

use Domain\Customer\Controllers\Base\BaseCustomerController;
use Domain\Finance\Models\CompensationItem;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Illuminate\Http\Request;

class CustomerCompensationItemController extends BaseCustomerController
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
     * customer's CompensationItem list
     */
    public function list(Request $request)
    {
        return $this->renderContent($request, 'project.customer.compensation-item.list', __('project.CompensationItemList'), [
        ]);
    }

    /**
     * customer's CompensationItem bulkUpload interface
     */
    public function bulkUpload(Request $request)
    {
        return $this->renderContent($request, 'project.customer.compensation-item.bulk-upload', __('project.CompensationItemBulkUpload'), [
        ]);
    }

    public function edit(string $accessToken, ?CompensationItem $compensationItem, Request $request)
    {
        // dump($compensationItem);exit;
        return $this->renderContent($request, 'project.customer.compensation-item.edit', __('project.CompensationItemEdit'), [
            // 'menu' => $this->getMenuData(),
            'compensationItem' => $compensationItem,
            'actionType' => $compensationItem->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
