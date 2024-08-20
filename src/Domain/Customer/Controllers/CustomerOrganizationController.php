<?php

namespace Domain\Customer\Controllers;

use Domain\Customer\Controllers\Base\BaseCustomerController;
use Domain\Customer\Models\Organization;
use Domain\Finance\Models\CompensationItem;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Illuminate\Http\Request;

class CustomerOrganizationController extends BaseCustomerController
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
        return $this->renderContent($request, 'customer.organization.list', __('customer.OrganizationList'), [
        ]);
    }

    public function edit(string $accessToken, ?Organization $organization, Request $request)
    {
        // dump($organization);exit;
        return $this->renderContent($request, 'customer.organization.edit', __('customer.OrganizationEdit'), [
            // 'menu' => $this->getMenuData(),
            'organization' => $organization,
            'actionType' => $organization->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
