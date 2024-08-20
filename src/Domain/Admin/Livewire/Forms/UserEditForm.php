<?php

namespace Domain\Admin\Livewire\Forms;

use Domain\Admin\Models\User;
use Domain\Admin\Rules\UserRules;
use Domain\Shared\Helpers\PHPHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Domain\User\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class UserEditForm extends BaseLivewireForm implements EditFormInterface
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
    public ?User $user;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $firstname = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $lastname = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $email = '';
    // ->ignore($this->user->email)

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $password = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $technicalPassword = '';

    public $role_data = [];

    public function getModelRules()
    {
        $formProperties = [];
        foreach (self::getFormData() as $formDataRow) {
            $formProperties[] = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        }

        $rules = [];
        $rulesBase = UserRules::rules($this->user->id);
        foreach ($rulesBase as $property => $ruleSet) {
            if (PHPHelper::inArray($property, $formProperties)) {
                $rules[$property] = $ruleSet;
            }
        }

        return $rules;
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        return [
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => User::PROPERTY_LAST_NAME,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Lastname',
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => User::PROPERTY_FIRST_NAME,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Firstname',
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => User::PROPERTY_EMAIL,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Email',
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'technicalPassword',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Password',
            ],
            // [
            //     BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'retypedPassword',
            //     BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => BaseLivewireForm::INPUT_TYPE_TEXT,
            //     BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'admin.RetypedPassword',
            // ],
        ];
    }

    /**
     * This method varies!
     * The name also varies: set{EntityName}
     */
    public function setUser(User $user)
    {
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->user = $user;

        /**
         * Keep this!
         */
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
            $this->{$property} = $user->{$property};
        }

        /**
         * This part varies!
         * Updating properties visibility.
         */
        $this->technicalPassword = '';
    }

    public function initRoleData()
    {
        $rolesOfAdmin = $this->user->roles()->get();
        foreach ($rolesOfAdmin as $roleOfAdmin) {
            if ($roleOfAdmin instanceof Role) {
                $attributes = $roleOfAdmin->getAttributes();
                $this->role_data[$attributes['name']] = true;
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
                $this->user->{$property} = $this->{$property};
                $properties[] = $property;
            }
        }

        /**
         * This part varies!
         * Put here the customization of settings the entity-object values!
         */
        if (! empty($this->technicalPassword)) {
            $this->user->password = Hash::make($this->technicalPassword);
        }

        /**
         * This property varies!
         */
        $this->user->saveWithRoleData($this->role_data);
    }

    public function recalculateRoleData()
    {
        $roles = Role::adminRoles()->get();
        $roleData = [];
        foreach ($roles as $role) {
            if (! array_key_exists($role->name, $this->role_data) || ! $this->role_data[$role->name]) {
                $roleData[$role->name] = false;
            } elseif ($this->role_data[$role->name]) {
                $roleData[$role->name] = true;
            }
        }

        $this->role_data = $roleData;
    }
}
