<?php

namespace Domain\Communication\Livewire;

use Domain\Communication\Models\CommunicationDispatch;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\UserService;

class EmailDispatchView extends BaseEditComponent
{
    // public EmailDispatchEditForm $form;

    public CommunicationDispatch $communicationDispatch;

    public $actionType = BaseEditComponent::ACTION_TYPE_VIEW;

    public static function getComponentName(): string
    {
        return 'email-dispatch-view';
    }

    public function mount(CommunicationDispatch $communicationDispatch)
    {
        $communicationDispatch = CommunicationDispatch::listableByCustomer()->where(['communicationdispatches.id' => $communicationDispatch->id])->first();
        if (! $communicationDispatch) {
            return redirect(UserService::getLogoutRoute(UserService::ROLE_TYPE_CUSTOMER));
        }
    }

    public function getEntityObject()
    {
        return $this->communicationDispatch;
    }

    public function render()
    {
        $viewParams = [
            'modelObject' => $this->communicationDispatch,
        ];

        return view('communication.email-dispatch.view-content', $viewParams);
    }
}
