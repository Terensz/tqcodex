<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Livewire\Forms\RoleEditForm;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Models\Role;
use Domain\User\Services\PermissionService;
use Domain\User\Services\RolePermissionDataService;
use Domain\User\Services\UserService;

class RoleEdit extends BaseEditComponent
{
    public RoleEditForm $form;

    public $actionType;

    public static function getComponentName(): string
    {
        return 'role-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'admin.user.role.edit',
            // 'paramArray' => ['role' => $this->getEntityObject()],
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_ADMIN, ['role' => $this->getEntityObject()]),
        ];
    }

    public function mount(Role $role, string $actionType)
    {
        // dump($actionType);exit;
        $this->form->setRole($role);
        $this->actionType = $actionType;
        $this->form->initPermissionData();
    }

    public function getEntityObject()
    {
        return $this->form->role;
    }

    public function checkAllPermissionInGuard()
    {
        if ($this->form->check_all[$this->form->guard_name] === true) {
            return $this->refreshView(true, false, false, false);
        }

        return $this->refreshView(false, true, false, false);
    }

    public function checkAllPermissionInSuffix($targetSuffix)
    {
        if ($this->form->permission_data[$this->form->guard_name][$targetSuffix][PermissionService::PREFIX_ALL] === true) {
            return $this->refreshView(false, false, $targetSuffix, false);
        }

        return $this->refreshView(false, false, false, $targetSuffix);
    }

    public function refreshGuardPermissions()
    {
        return $this->refreshView(false, false, false, false);
    }

    public function refreshView($checkAllInGuardRequested = false, $decheckAllInGuardRequested = false, $checkAllInSuffixRequested = false, $decheckAllInSuffixRequested = false)
    {
        return $this->render($checkAllInGuardRequested, $decheckAllInGuardRequested, $checkAllInSuffixRequested, $decheckAllInSuffixRequested);
    }

    public function render($checkAllInGuardRequested = false, $decheckAllInGuardRequested = false, $checkAllInSuffixRequested = false, $decheckAllInSuffixRequested = false)
    {
        $this->form->recalculatePermissionData($checkAllInGuardRequested, $decheckAllInGuardRequested, $checkAllInSuffixRequested, $decheckAllInSuffixRequested);

        $cachedRolePermissionData = RolePermissionDataService::getCachedRolePermissionData($this->form->role->name);

        $guardPermissionData = $this->form->permission_data[$this->form->guard_name];

        $viewParams = [
            'actionType' => $this->actionType,
            'formData' => RoleEditForm::getFormData(),
            'pageTitle' => $this->form->role->id ? __('shared.EditItem', ['item' => __('role.Role')]) : __('shared.CreateNewItem', ['item' => __('role.Role')]),
            'pageShortDescription' => __('permission.EditRoleDescription'),
            'cachedRolePermissionData' => $cachedRolePermissionData,
            'guardPermissionData' => $guardPermissionData,
            'guardName' => $this->form->guard_name,
        ];

        return view('admin.role.edit-form', $viewParams);
    }
}
