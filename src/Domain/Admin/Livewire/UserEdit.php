<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Livewire\Forms\UserEditForm;
use Domain\Admin\Models\User;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;

class UserEdit extends BaseEditComponent
{
    public UserEditForm $form;

    public $actionType;

    public static function getComponentName(): string
    {
        return 'user-edit';
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => 'admin.admin.user.edit',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_ADMIN, ['user' => $this->getEntityObject()]),
        ];
    }

    public function mount(User $user, string $actionType)
    {
        $this->form->setUser($user);
        $this->actionType = $actionType;
        $this->form->initRoleData();

        if (UserService::getAdmin() && UserService::getAdmin()->id === $this->getEntityObject()->id) {
            return redirect()->route('admin.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]);
        }
    }

    public function getEntityObject()
    {
        return $this->form->user;
    }

    public function render()
    {
        $this->form->recalculateRoleData();

        $viewParams = [
            'formData' => UserEditForm::getFormData(),
            'pageTitle' => $this->form->user->id ? __('shared.EditItem', ['item' => __('admin.Admin')]) : __('shared.CreateNewItem', ['item' => __('admin.Admin')]),
            'pageShortDescription' => __('admin.EditAdminDescription'),
            'userRoleData' => $this->form->role_data,
        ];

        return view('admin.user.edit-form', $viewParams);
    }
}
