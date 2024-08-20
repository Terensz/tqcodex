<?php

namespace Domain\Customer\Controllers;

use Domain\Customer\Controllers\Base\BaseCustomerController;
use Domain\Customer\Models\OrgAddress;
use Domain\Finance\Models\CompensationItem;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Illuminate\Http\Request;

class CustomerOrgAddressController extends BaseCustomerController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_ORGANIZATIONS_CUSTOMER_INTERFACE;
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
        return $this->renderContent($request, 'customer.org-address.list', __('customer.OrgAddressList'), [
        ]);
    }

    public function edit(string $accessToken, ?OrgAddress $orgAddress, Request $request)
    {
        // dump($organization);exit;
        return $this->renderContent($request, 'customer.org-address.edit', __('customer.OrgAddressEdit'), [
            // 'menu' => $this->getMenuData(),
            'orgAddress' => $orgAddress,
            'actionType' => $orgAddress->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
