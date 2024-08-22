<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Livewire\Forms\ContactEditForm;
use Domain\Customer\Models\Contact;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

class ContactEdit extends BaseEditComponent
{
    public ContactEditForm $form;

    public $actionType;

    public static function getComponentName(): string
    {
        return 'contact-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'admin.customer.contact.edit',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_ADMIN, ['contact' => $this->getEntityObject()]),
        ];
    }

    public function mount(Contact $contact, string $actionType)
    {
        $this->form->setContact($contact);
        $this->actionType = $actionType;
        $this->form->initRoleData();
    }

    public function getEntityObject()
    {
        return $this->form->contact;
    }

    public function navigateToImpersonateCustomer()
    {
        $url = route('admin.admin.contact.impersonate', [
            'access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN),
            'contact' => $this->form->contact->id,
        ]);
        // $url = route('impersonate', ['id' => $this->form->contact->id, 'guardName' => UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER)]);

        return redirect($url);
    }

    public function render()
    {
        $this->form->recalculateRoleData();

        $viewParams = [
            'formData' => ContactEditForm::getFormData(),
            'pageTitle' => $this->form->contact->id ? __('shared.EditItem', ['item' => __('customer.Customer')]) : __('shared.CreateNewItem', ['item' => __('customer.Customer')]),
            'pageShortDescription' => __('customer.EditCustomerDescription'),
            'middleButton' => [
                'active' => $this->form->contact->id ? true : false,
                'label' => __('admin.ImpersonateCustomer'),
                'backendMethod' => 'navigateToImpersonateCustomer',
            ],
            'contactRoleData' => $this->form->role_data,
            'showContactProfile' => $this->form->contact->getContactProfile() ? true : (! $this->form->contact->id ? true : false),
            // 'editRouteName' => 'admin.admin.user.edit'
        ];

        return view('admin.customer.contact.edit-form', $viewParams);
    }
}
