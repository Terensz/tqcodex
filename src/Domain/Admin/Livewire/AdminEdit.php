<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Livewire\Forms\AdminEditForm;
use Domain\Admin\Models\Admin;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

class AdminEdit extends BaseEditComponent
{
    public AdminEditForm $form;

    public $actionType;

    public static function getComponentName(): string
    {
        return 'admin-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'admin.admin.edit',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_ADMIN, ['user' => $this->getEntityObject()]),
        ];
    }

    public function mount(Admin $admin, string $actionType)
    {
        $this->form->setAdmin($admin);
        $this->actionType = $actionType;
        $this->form->initRoleData();

        if (UserService::getAdmin() && UserService::getAdmin()->id === $this->getEntityObject()->id) {
            return redirect()->route('admin.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]);
        }
    }

    public function getEntityObject()
    {
        return $this->form->admin;
    }

    public function render()
    {
        $this->form->recalculateRoleData();

        $viewParams = [
            'formData' => AdminEditForm::getFormData(),
            'pageTitle' => $this->form->admin->id ? __('shared.EditItem', ['item' => __('admin.Admin')]) : __('shared.CreateNewItem', ['item' => __('admin.Admin')]),
            'pageShortDescription' => __('admin.EditAdminDescription'),
            'userRoleData' => $this->form->role_data,
        ];

        return view('admin.admin.edit-form', $viewParams);
    }
}
