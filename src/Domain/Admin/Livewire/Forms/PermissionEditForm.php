<?php

namespace Domain\Admin\Livewire\Forms;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Model;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class PermissionEditForm extends BaseLivewireForm implements EditFormInterface
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
     * This property varies!
     * Overwrite it with the actual entity-object name.
     */
    public ?Model $permission;

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

    public function getModelRules()
    {
        return [
            'name' => [
                'required',
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
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'permission.Name',
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'guard_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'permission.GuardName',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $guardNameOptions,
            ],
        ];
    }

    /**
     * This method varies!
     * The name also varies: set{EntityName}
     */
    public function setPermission(Model $permission)
    {
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->permission = $permission;

        /**
         * Keep this!
         */
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
            $this->{$property} = $permission->{$property};
        }

        /**
         * This part varies!
         * Updating properties visibility.
         */
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
                $this->permission->{$property} = $this->{$property};
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
        $this->permission->save();
    }
}
