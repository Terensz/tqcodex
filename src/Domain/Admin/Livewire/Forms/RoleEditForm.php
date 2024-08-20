<?php

namespace Domain\Admin\Livewire\Forms;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Domain\User\Models\Permission;
use Domain\User\Models\Role;
use Domain\User\Services\PermissionService;
use Domain\User\Services\RolePermissionDataService;
use Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Model;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class RoleEditForm extends BaseLivewireForm implements EditFormInterface
{
    /**
     * This constant is not required.
     * If exists: these properties will not updated in the entity-object.
     */
    public const TECHNICAL_PROPERTIES = [
        'technicalPassword',
        // 'retypedPassword'
    ];

    /**
     * @var Role
     */
    public ?Model $role;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $name = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $guard_name = '';

    public $permission_data = [];

    public $check_all = [];

    public function getModelRules()
    {
        return [
            'name' => [
                'required',
                'alpha_num',
            ],
            'guard_name' => [
                'required',
            ],
        ];
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        $guardNameOptions = [];
        $guardNameOptions[] = [
            ValidationHelper::OPTION_VALUE => null,
            ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
        ];
        foreach (UserService::GUARDS as $roleType => $guardName) {
            $guardNameOptions[] = [
                ValidationHelper::OPTION_VALUE => $guardName,
                ValidationHelper::OPTION_LABEL => __('admin.'.$roleType),
            ];
        }

        return [
            'name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'role.Name',
            ],
            'guard_name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'guard_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'role.GuardName',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $guardNameOptions,
            ],
        ];
    }

    /**
     * This method varies!
     * The name also varies: set{EntityName}
     */
    public function setRole(Model $role)
    {
        /**
         * @var Role $role
         */
        $this->role = $role;

        /**
         * Keep this!
         */
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
            $this->{$property} = $role->{$property};
        }

        /**
         * This part varies!
         * Updating properties visibility.
         */
    }

    public function initPermissionData()
    {
        $permissionsOfRole = $this->role->permissions()->get();
        foreach ($permissionsOfRole as $permissionOfRole) {
            if ($permissionOfRole instanceof Permission) {
                $nameParts = PermissionService::separatePermissionName($permissionOfRole->name);
                $this->permission_data[$permissionOfRole->guard_name][$nameParts['suffix']][$nameParts['prefix']] = true;
            }
        }
    }

    /**
     * This method varies!
     */
    public function store()
    {
        /**
         * Keep this!
         */
        $this->validate($this->getModelRules());

        /**
         * Keep this!
         * Modify entity name on 1 place.
         */
        $properties = [];
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            if (! defined('self::TECHNICAL_PROPERTIES') || (defined('self::TECHNICAL_PROPERTIES') && ! in_array($property, self::TECHNICAL_PROPERTIES))) {
                $this->role->{$property} = $this->{$property};
                $properties[] = $property;
            }
        }

        /**
         * This part varies!
         * Put here the customization of settings the entity-object values!
         */

        /**
         * This property varies!
         */
        $this->role->saveWithPermissionData($this->permission_data);
    }

    public function recalculatePermissionData($checkAllInGuardRequested = false, $decheckAllInGuardRequested = false, $checkAllInSuffixRequested = false, $decheckAllInSuffixRequested = false)
    {
        if (! isset($this->permission_data[$this->guard_name])) {
            $this->permission_data[$this->guard_name] = [];
        }
        if (! isset($this->check_all[$this->guard_name])) {
            $this->check_all[$this->guard_name] = [];
        }

        $recalculatedGuardRolePermissionData = RolePermissionDataService::recalculateGuardRolePermissionData($this->name, $this->permission_data[$this->guard_name], $this->guard_name, $checkAllInGuardRequested, $decheckAllInGuardRequested, $checkAllInSuffixRequested, $decheckAllInSuffixRequested);

        $this->check_all[$this->guard_name] = $recalculatedGuardRolePermissionData['allPrefixesAreTrue'];
        $this->permission_data[$this->guard_name] = $recalculatedGuardRolePermissionData['guardRolePermissionData'];
    }
}
