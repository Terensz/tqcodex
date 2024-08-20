<?php

namespace Domain\Admin\Livewire\Forms;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Domain\Shared\Rules\ModelExists;
use Domain\User\Models\Permission;
use Domain\User\Models\Role;
use Domain\User\Models\RoleHasPermission;
use Illuminate\Database\Eloquent\Model;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class RoleHasPermissionEditForm extends BaseLivewireForm implements EditFormInterface
{
    public ?RoleHasPermission $roleHasPermission;

    public $role_id = '';

    public $permission_id = '';

    public $testprop = '';

    public function getModelRules()
    {
        return [
            'role_id' => [
                'required',
                // new ModelExists($this->roleHasPermission->role),
            ],
            'permission_id' => [
                'required',
                // new ModelExists($this->roleHasPermission->permission),
            ],
        ];
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        $roleOptions = [];
        $roleOptions[] = [
            ValidationHelper::OPTION_VALUE => null,
            ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
        ];
        foreach (Role::all() as $role) {
            $roleOptions[] = [
                ValidationHelper::OPTION_VALUE => $role->id,
                ValidationHelper::OPTION_LABEL => $role->name,
            ];
        }

        $permissionOptions = [];
        $permissionOptions[] = [
            ValidationHelper::OPTION_VALUE => null,
            ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
        ];
        foreach (Permission::all() as $permission) {
            $permissionOptions[] = [
                ValidationHelper::OPTION_VALUE => $permission->id,
                ValidationHelper::OPTION_LABEL => $permission->name,
            ];
        }

        return [
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'role_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'role.Role',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $roleOptions,
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'permission_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'permission.Permission',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $permissionOptions,
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'testprop',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'permission.Permission',
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
        ];
    }

    /**
     * This method varies!
     * The name also varies: set{EntityName}
     */
    public function setRoleHasPermission(RoleHasPermission $roleHasPermission)
    {
        $this->roleHasPermission = $roleHasPermission;
        if ($roleHasPermission->role) {
            $this->role_id = $roleHasPermission->role->id;
        }
        if ($roleHasPermission->permission) {
            $this->permission_id = $roleHasPermission->permission->id;
        }
        // $this->role_id = $roleHasPermission->role ? $roleHasPermission->role->id : null;
        // $this->permission_id = $roleHasPermission->permission ? $roleHasPermission->permission->id : null;
    }

    // public function setRoleHasPermission(RoleHasPermission $roleHasPermission)
    // {
    //     $this->roleHasPermission = $roleHasPermission;
    //     $this->role = $roleHasPermission->role ? : new Role();
    //     $this->permission = $roleHasPermission->permission ? : new Permission();
    // }

    /**
     * This method varies!
     */
    public function store()
    {
        $role = Role::find($this->role_id);
        if ($this->roleHasPermission instanceof RoleHasPermission && (! $role || $role instanceof Role)) {
            $this->roleHasPermission->role_id = $role ? $role->id : null;
            $permission = Permission::find($this->permission_id);
            if (! $permission || $permission instanceof Permission) {
                $this->roleHasPermission->permission_id = $permission ? $permission->id : null;
            }
        }
        try {
            $this->validate($this->getModelRules());

            $this->roleHasPermission->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
