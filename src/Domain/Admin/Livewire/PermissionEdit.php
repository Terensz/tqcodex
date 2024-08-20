<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Livewire\Forms\PermissionEditForm;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\Permission;
use Domain\User\Services\UserService;

class PermissionEdit extends BaseEditComponent
{
    public PermissionEditForm $form;

    public $actionType;

    public static function getComponentName(): string
    {
        return 'permission-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'admin.user.permission.edit',
            // 'paramArray' => ['permission' => $this->getEntityObject()],
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_ADMIN, ['permission' => $this->getEntityObject()]),
        ];
    }

    public function mount(Permission $permission, string $actionType)
    {
        $this->form->setPermission($permission);
        $this->actionType = $actionType;
    }

    public function getEntityObject()
    {
        return $this->form->permission;
    }

    public function render()
    {
        $viewParams = [
            'formData' => PermissionEditForm::getFormData(),
            /** @phpstan-ignore-next-line  */
            'pageTitle' => $this->form->permission->id ? __('shared.EditItem', ['item' => __('permission.Permission')]) : __('shared.CreateNewItem', ['item' => __('permission.Permission')]),
            'pageShortDescription' => __('permission.EditPermissionDescription'),
        ];

        return view('common.general-edit-form', $viewParams);
    }
}
