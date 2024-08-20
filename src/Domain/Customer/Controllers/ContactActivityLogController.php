<?php

namespace Domain\Customer\Controllers;

use Domain\Customer\Controllers\Base\BaseCustomerController;
use Domain\Customer\Models\ContactActivityLog;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Illuminate\Http\Request;

/**
 * CRUD controller for ContactActivityLog
 */
class ContactActivityLogController extends BaseCustomerController
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
        return $this->renderContent($request, 'admin.customer.contact-activity-log.list', __('shared.ListOfItem', ['plural_item' => __('system.ContactActivityLogs')]), [
            // 'menu' => $this->getMenuData(),
            'user' => $this->getUserData(),
            // 'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/user-edit',
        ]);
    }

    /**
     * New + Edit
     */
    // public function edit(ContactActivityLog $contactActivityLog, Request $request)
    // {
    //     return $this->renderContent($request, 'customer.activity-log.edit', 'CustomerActivityLogEdit', [
    //         // 'menu' => $this->getMenuData(),
    //         'contactActivityLog' => $contactActivityLog,
    //         'actionType' => $contactActivityLog->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
    //     ]);
    // }
}
