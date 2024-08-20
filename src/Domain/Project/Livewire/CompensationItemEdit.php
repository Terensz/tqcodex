<?php

namespace Domain\Project\Livewire;

use Domain\Customer\Models\Organization;
use Domain\Finance\Models\CompensationItem;
use Domain\Finance\Models\PartnerOrg;
use Domain\Project\Livewire\Forms\CompensationItemEditForm;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

final class CompensationItemEdit extends BaseEditComponent
{
    public CompensationItemEditForm $form;

    public $inviteOrganization;

    // # 1

    public $temp_partner_org_id;

    // # 2

    public $temp_partner_name;

    public $temp_partner_taxpayer_id;

    public $temp_partner_eutaxid;

    public $temp_partner_address;

    public $temp_partner_email;

    public $temp_partner_phone;

    public $temp_partner_contact;

    public static function getComponentName(): string
    {
        return 'compensation-item-edit';
    }

    public function setTempInviteProperties(): void
    {
        // Invite
        if ($this->inviteOrganization) {
            $this->setPartnerOrgNameByOrgId();
            // Filling temps
            $this->temp_partner_org_id = $this->form->partner_org_id;
            // Setting nulls on form
            $this->form->partner_org_id = null;
            // Filling form
            $this->form->partner_name = $this->form->partner_name ?: $this->temp_partner_name;
            $this->form->partner_taxpayer_id = $this->temp_partner_taxpayer_id;
            $this->form->partner_eutaxid = $this->temp_partner_eutaxid;
            $this->form->partner_address = $this->temp_partner_address;
            $this->form->partner_email = $this->temp_partner_email;
            $this->form->partner_phone = $this->temp_partner_phone;

            // $this->form->partner_contact = $this->temp_partner_contact;
            return;
        }
        // Filling temps
        $this->temp_partner_name = $this->form->partner_name;
        $this->temp_partner_taxpayer_id = $this->form->partner_taxpayer_id;
        $this->temp_partner_eutaxid = $this->form->partner_eutaxid;
        $this->temp_partner_address = $this->form->partner_address;
        $this->temp_partner_email = $this->form->partner_email;
        $this->temp_partner_phone = $this->form->partner_phone;
        // $this->temp_partner_contact = $this->form->partner_contact;
        // Setting nulls on form
        // $this->form->partner_name = null;
        $this->form->partner_taxpayer_id = null;
        $this->form->partner_eutaxid = null;
        $this->form->partner_address = null;
        $this->form->partner_email = null;
        $this->form->partner_phone = null;
        // $this->form->partner_contact = null;
        // Filling form
        $this->form->partner_org_id = $this->temp_partner_org_id;
        $this->setPartnerOrgNameByOrgId();

    }

    public function findPartnerOrg(int $id)
    {
        return PartnerOrg::potentialPartner()->find($id);
        // dump($alma->toSql());
        // dump($alma->getBindings());
        // dump($alma->first());exit;
    }

    public function setPartnerOrgNameByOrgId()
    {
        if ($this->form->partner_org_id) {
            $partnerOrg = $this->findPartnerOrg($this->form->partner_org_id);
            if ($partnerOrg) {
                $this->form->partner_name = $partnerOrg->name;

                return;
            }
            // else {
            //     $this->form->partner_org_id = null;
            // }
        }

        /**
         * We are always checking that the partner_org_id we have is valid or not.
         */
        $this->form->partner_org_id = null;
    }

    public function toggleInviteOrganization($checkedState)
    {
        $this->inviteOrganization = $checkedState;
        $this->setTempInviteProperties();

        return $this->refreshView();
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'customer.project.compensation-item.edit',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_CUSTOMER, ['compensationItem' => $this->getEntityObject()]),
        ];
    }

    public function boot() {}

    public function mount(CompensationItem $compensationItem, string $actionType)
    {
        // dump($compensationItem);exit;
        $allowedObject = ! $compensationItem->id ? $compensationItem : CompensationItem::listableByCustomer()->where('compensationitems.id', '=', $compensationItem->id)->first();
        if (! $allowedObject) {
            return redirect()->route('customer.project.compensation-item.list', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]);
        }

        $this->inviteOrganization = empty($compensationItem->partner_org_id);
        $this->setTempInviteProperties();

        $this->form->setCompensationItem($compensationItem);
        $this->actionType = $actionType;

        $dropdownId = 'partner_org_id';
        $this->dropdownParams[$dropdownId] = [
            // 'showList' => 'false',
            // 'showChevronUp' => 'false',
            // 'showChevronDown' => 'true',
            // 'filteredData' => [],
            // 'config' => [
            //     'stringProperty' => 'partner_org_string'
            // ],
            'showList' => false,
            'showChevronUp' => false,
            'showChevronDown' => true,
            'searchedString' => '',
            'filteredData' => [],
            // 'builderMethodClass' => ,
            // 'builderMethodName' =>'getBuilder',
            // 'selected' => null,
        ];
        $this->refreshFilteredDropdownData($dropdownId);
    }

    public function getEntityObject()
    {
        return $this->form->compensationItem;
    }

    public function save()
    {
        $this->setPartnerOrgNameByOrgId();
        parent::save();
    }

    public function refreshFilteredDropdownData($dropdownId)
    {
        $filteredData = [];
        foreach (PartnerOrg::potentialPartner()->nameLike($this->dropdownParams[$dropdownId]['searchedString'])->get() as $orgObject) {
            $orgPrimaryContactString = null;
            $orgContactProfileCollection = $orgObject->contactProfiles()->get();
            foreach ($orgContactProfileCollection as $orgContactProfile) {
                // Csak az első kell.
                if (! $orgPrimaryContactString) {
                    $orgPrimaryContactString = $orgContactProfile->getFullName().', '.$orgContactProfile->email;
                    // $orgPrimaryContactData = [
                    //     'id' => $orgContactProfile->id,
                    //     'name' => $orgContactProfile->getFullName(),
                    //     'email' => $orgContactProfile->email,
                    //     'mobile' => $orgContactProfile->mobile,
                    // ];
                }
            }

            $filteredData[] = [
                'id' => $orgObject->id,
                'label' => $orgPrimaryContactString ?: '',
                'value' => $orgObject->name,
                // 'primaryContactData' => $orgPrimaryContactData
            ];
        }

        // exit;

        $this->dropdownParams[$dropdownId]['filteredData'] = $filteredData;

        return $this->refreshView();
    }

    public function refreshFilteredDropdownOptions($dropdownId)
    {
        $this->dropdownParams[$dropdownId]['showList'] = true;
        $this->dropdownParams[$dropdownId]['showChevronDown'] = true;
        $this->dropdownParams[$dropdownId]['showChevronUp'] = false;

        return $this->refreshView();
        // $this->dispatch('push-filtered-dropdown-options', ['dropdownId' => $dropdownId]);
    }

    // Javascript-triggered methods

    public function initOpenDropdown($dropdownId)
    {
        // $this->dropdownParams[$dropdownId]['showList'] = 'true';
        // $this->dropdownParams[$dropdownId]['showChevronDown'] = 'true';
        // $this->dropdownParams[$dropdownId]['showChevronUp'] = 'false';
        $this->dropdownParams[$dropdownId]['showList'] = true;
        $this->dropdownParams[$dropdownId]['showChevronDown'] = true;
        $this->dropdownParams[$dropdownId]['showChevronUp'] = false;

        return $this->refreshView();
        // $this->dispatch('open-dropdown', ['dropdownId' => $dropdownId]);
    }

    public function initCloseDropdown($dropdownId)
    {
        $this->dropdownParams[$dropdownId]['showList'] = false;
        $this->dropdownParams[$dropdownId]['showChevronDown'] = false;
        $this->dropdownParams[$dropdownId]['showChevronUp'] = true;

        return $this->refreshView();
        // $this->dispatch('close-dropdown', ['dropdownId' => $dropdownId]);
    }

    public function initStringTypingFinished($dropdownId, $string)
    {
        // dump($dropdownId, $string); exit;
        $this->dropdownParams[$dropdownId]['searchedString'] = $string;
        $this->refreshFilteredDropdownData($dropdownId);

        return $this->refreshView();
    }

    public function initSelectDropdownOption($dropdownId, $optionId)
    {
        $this->form->partner_org_id = $optionId;

        return $this->refreshView();
    }

    public function initRemoveSelectedDropdownOption($dropdownId)
    {
        $this->form->partner_org_id = null;
        $this->inviteOrganization = true;
        $this->setTempInviteProperties();

        return $this->refreshView();
    }

    // public function toggleDropdown($dropdownId)
    // {
    //     $this->dispatch('toggle-dropdown', ['dropdownId' => $dropdownId]);
    // }

    // public function triggerSelectDropdownOption($dropdownId)
    // {
    //     $this->dispatch('select-dropdown-option', ['dropdownId' => $dropdownId]);
    // }

    // public function triggerFocusPrevDropdownOption($dropdownId)
    // {
    //     $this->dispatch('focus-prev-dropdown-option', ['dropdownId' => $dropdownId]);
    // }

    // public function triggerFocusNextDropdownOption($dropdownId)
    // {
    //     $this->dispatch('focus-next-dropdown-option', ['dropdownId' => $dropdownId]);
    // }

    public function refreshView()
    {
        return $this->render();
    }

    public function render()
    {
        // dump($this->form);exit;
        // dump($this->dropdownParams);exit;
        $viewParams = [
            'formData' => CompensationItemEditForm::getFormData(),
            'inviteOrganization' => $this->inviteOrganization,
            'pageTitle' => $this->form->compensationItem->id ? __('shared.EditItem', ['item' => __('project.CompensationItem')]) : __('shared.CreateNewItem', ['item' => __('project.CompensationItem')]),
            'pageShortDescription' => __('project.EditCompensationItemDescription'),
            'dropdownParams' => $this->dropdownParams,
            'partnerOrg' => $this->form->partner_org_id ? Organization::find($this->form->partner_org_id) : null,
        ];

        return view('project.customer.compensation-item.edit-form', $viewParams);
    }
}
