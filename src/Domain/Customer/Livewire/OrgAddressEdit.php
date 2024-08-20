<?php

namespace Domain\Customer\Livewire;

use Domain\Customer\Livewire\Forms\OrgAddressEditForm;
use Domain\Customer\Livewire\Forms\OrganizationEditForm;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Services\OrganizationService;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\UserService;

final class OrgAddressEdit extends BaseEditComponent
{
    public OrgAddressEditForm $form;

    // public $actionType;

    public static function getComponentName(): string
    {
        return 'org-address-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'customer.org-address.edit',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_CUSTOMER, ['orgAddress' => $this->getEntityObject()]),
        ];
    }

    public function boot() {}

    public function mount(OrgAddress $orgAddress, string $actionType)
    {
        // dump($organization);exit;
        $organizationBindings = OrganizationService::getOrganizationBindings($orgAddress->getOrganization());
        /**
         * In this case the current user tried to open the organization of someone else.
         */
        if (! $organizationBindings['currentContactBinds'] && $this->actionType === self::ACTION_TYPE_EDIT) {
            // dump(UserService::getLoginRoute(UserService::ROLE_TYPE_CUSTOMER));exit;
            return redirect(UserService::getLogoutRoute(UserService::ROLE_TYPE_CUSTOMER));
        }
        // exit;

        $this->form->setOrgAddress($orgAddress);
        $this->actionType = $actionType;

        // $dropdownId = 'partner_org_id';
        // $this->dropdownParams[$dropdownId] = [
        //     // 'showList' => 'false',
        //     // 'showChevronUp' => 'false',
        //     // 'showChevronDown' => 'true',
        //     // 'filteredData' => [],
        //     'showList' => false,
        //     'showChevronUp' => false,
        //     'showChevronDown' => true,
        //     'filteredData' => [],
        //     // 'builderMethodClass' => ,
        //     // 'builderMethodName' =>'getBuilder',
        //     // 'selected' => null,
        // ];
        // $this->refreshFilteredData($dropdownId);
    }

    public function getEntityObject()
    {
        return $this->form->orgAddress;
    }

    public function refreshView()
    {
        return $this->render();
    }

    public function render()
    {
        // dump($this->form->organization);
        // dump($this->form->organization->contacts()->get());
        // exit;
        $viewParams = [
            'formData' => OrganizationEditForm::getFormData(),
            'pageTitle' => $this->form->orgAddress->id ? __('shared.EditItem', ['item' => __('customer.OrgAddress')]) : __('shared.CreateNewItem', ['item' => __('customer.OrgAddress')]),
            'pageShortDescription' => __('customer.EditOrgAddressDescription'),
            'dropdownParams' => $this->dropdownParams,
        ];

        // return view('project.customer.organization.edit-form', $viewParams);
        return view('common.general-edit-form', $viewParams);
    }
}
