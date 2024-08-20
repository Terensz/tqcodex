<?php

namespace Domain\Customer\Livewire;

use Domain\Customer\Livewire\Forms\OrgAddressEditForm;
use Domain\Customer\Livewire\Forms\OrganizationEditForm;
use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Customer\Services\OrganizationService;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\UserService;
use Illuminate\Database\QueryException;

final class OrganizationEdit extends BaseEditComponent
{
    public OrganizationEditForm $form;

    public OrgAddressEditForm $mainAddressForm;

    // public $addressForms = [
    //     CorporateAddressType::HEADQUARTERS->value
    // ];

    // public $actionType;

    public static function getComponentName(): string
    {
        return 'organization-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'customer.organization.edit',
            // 'paramArray' => ['organization' => $this->getEntityObject()],
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_CUSTOMER, ['organization' => $this->getEntityObject()]),
        ];
    }

    public function boot() {}

    public function mount(Organization $organization, string $actionType)
    {
        $organizationBindings = OrganizationService::getOrganizationBindings($organization);

        /**
         * In this case the current user tried to open the organization of someone else.
         */
        if (! $organizationBindings['currentContactBinds'] && $this->actionType === self::ACTION_TYPE_EDIT) {
            // dump(UserService::getLoginRoute(UserService::ROLE_TYPE_CUSTOMER));exit;
            return redirect(UserService::getLogoutRoute(UserService::ROLE_TYPE_CUSTOMER));
        }

        $this->actionType = $actionType;

        $this->form->setOrganization($organization);

        $mainAddress = OrgAddress::where(['organization_id' => $organization->id, 'main' => true])->first();

        if ($mainAddress && $mainAddress instanceof OrgAddress) {
            $this->mainAddressForm->setOrgAddress($mainAddress);
        }
    }

    public function getEntityObject()
    {
        return $this->form->organization;
    }

    public function refreshView()
    {
        return $this->render();
    }

    public function save()
    {
        try {
            if ($this->getForm()) {
                $this->getForm()->store();
                $this->mainAddressForm->store();
            }

            $dispatchData = [
                'editRoute' => $this->getEditRoute(),
                'actionType' => $this->actionType,
                'id' => $this->getEntityObject()->id,
            ];

            if (is_numeric($dispatchData['id']) && $dispatchData['id'] > 0 && $dispatchData['actionType'] == BaseEditComponent::ACTION_TYPE_NEW) {
                $routeComponents = $this->getEditRouteComponents();

                return redirect()->route($routeComponents['name'], $routeComponents['paramArray'])->with('success', __('shared.SaveSuccessful'));
            } else {
                $this->dispatch('save-successful', $dispatchData);
            }

            /**
             * This must not be \Exception, because than the validation system collapses.
             */
        } catch (QueryException $e) {
            $this->dispatch('save-failed');
        }
    }

    public function render()
    {
        // dump($this->hqAddressForm);exit;
        // dump($this->form->organization);
        // dump($this->form->organization->contacts()->get());
        // exit;
        $viewParams = [
            'formData' => OrganizationEditForm::getFormData(),
            'mainAddressFormData' => OrgAddressEditForm::getFormData(),
            'pageTitle' => $this->form->organization->id ? __('shared.EditItem', ['item' => __('customer.Organization')]) : __('shared.CreateNewItem', ['item' => __('customer.Organization')]),
            'pageShortDescription' => __('customer.EditOrganizationDescription'),
            'dropdownParams' => $this->dropdownParams,
        ];

        // return view('project.customer.organization.edit-form', $viewParams);
        return view('customer.organization.edit-form', $viewParams);
    }
}
