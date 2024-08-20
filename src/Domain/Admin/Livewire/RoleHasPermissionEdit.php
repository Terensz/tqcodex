<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Livewire\Forms\RoleHasPermissionEditForm;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\RoleHasPermission;
use Domain\User\Services\UserService;

class RoleHasPermissionEdit extends BaseEditComponent
{
    public RoleHasPermissionEditForm $form;

    public $actionType;

    public static function getComponentName(): string
    {
        return 'role-has-permission-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'admin.user.role-has-permission.edit',
            // 'paramArray' => ['roleHasPermission' => $this->getEntityObject()],
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_ADMIN, ['roleHasPermission' => $this->getEntityObject()]),
        ];
    }

    public function mount(RoleHasPermission $roleHasPermission, string $actionType)
    {
        $this->form->setRoleHasPermission($roleHasPermission);
        $this->actionType = $actionType;
    }

    public function getEntityObject()
    {
        return $this->form->roleHasPermission;
    }

    public function render()
    {
        $viewParams = [
            'formData' => RoleHasPermissionEditForm::getFormData(),
            'pageTitle' => $this->form->roleHasPermission->exists ? __('shared.EditItem', ['item' => __('permission.Permission')]) : __('shared.CreateNewItem', ['item' => __('permission.Permission')]),
            'pageShortDescription' => __('permission.EditPermissionDescription'),
        ];

        return view('common.general-edit-form', $viewParams);
    }
}
