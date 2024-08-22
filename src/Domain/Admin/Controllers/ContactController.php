<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Customer\Models\Contact;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

class ContactController extends BaseAdminController
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
    public function __construct()
    {
        // $this->middleware(['validate.customer', 'redirect.non.admin']);
    }

    /**
     * List
     */
    public function list(Request $request)
    {
        return $this->renderContent($request, 'admin.customer.contact.list', __('shared.ListOfItem', ['plural_item' => __('admin.Customers')]), [
            'menu' => $this->getMenuData(),
            'admin' => $this->getAdminData(),
            'formUrlBase' => '/'.UserService::getHome(UserService::ROLE_TYPE_ADMIN).'/contact-edit',
        ]);
    }

    /**
     * New + Edit
     */
    public function edit(string $accessToken, Contact $contact, Request $request)
    {
        return $this->renderContent($request, 'admin.customer.contact.edit', __('shared.EditItem', ['item' => __('admin.Customer')]), [
            'menu' => $this->getMenuData(),
            'contact' => $contact,
            'actionType' => $contact->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }

    public function impersonate(string $accessToken, Contact $contact, Request $request)
    {
        UserService::quietLogin(UserService::ROLE_TYPE_CUSTOMER, $contact);

        $contactAccessToken = AccessTokenService::isAccessToken(UserService::ROLE_TYPE_CUSTOMER) ? AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER) : null;
        if (! $contactAccessToken) {
            $contactAccessToken = AccessTokenService::createAccessToken();
            AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, $contactAccessToken);
        }

        return redirect(route('customer.dashboard', [
            'access_token' => $contactAccessToken,
        ]));
    }
}
