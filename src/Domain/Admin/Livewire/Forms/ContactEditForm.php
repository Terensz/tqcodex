<?php

namespace Domain\Admin\Livewire\Forms;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Rules\ContactRules;
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
class ContactEditForm extends BaseLivewireForm implements EditFormInterface
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
    public ?Contact $contact;

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

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $phone = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $mobile = '';

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
        $rulesBase = ContactRules::rules($this->contact->id);
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
            ContactProfile::PROPERTY_LAST_NAME => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => ContactProfile::PROPERTY_LAST_NAME,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Lastname',
            ],
            ContactProfile::PROPERTY_FIRST_NAME => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => ContactProfile::PROPERTY_FIRST_NAME,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Firstname',
            ],
            ContactProfile::PROPERTY_EMAIL => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => ContactProfile::PROPERTY_EMAIL,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Email',
            ],
            ContactProfile::PROPERTY_PHONE => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => ContactProfile::PROPERTY_PHONE,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Phone',
            ],
            ContactProfile::PROPERTY_MOBILE => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => ContactProfile::PROPERTY_MOBILE,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Mobile',
            ],
            'technicalPassword' => [
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
    public function setContact(Contact $contact)
    {
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->contact = $contact;

        /**
         * This part is special in this case, because out Contact model contains an additional model: ContactProfile,
         * which actually contains all the personal data.
         * If you want a general method for set{Model}, look for a general one, like setRoles() in the RoleEditForm component.
         */
        $contactProfile = $this->contact->getContactProfile();
        if ($contactProfile) {
            $this->firstname = $contactProfile->firstname;
            $this->lastname = $contactProfile->lastname;
            $this->email = $contactProfile->email;
            $this->phone = $contactProfile->phone;
            $this->mobile = $contactProfile->mobile;
            $this->password = $contact->password;
        }

        // foreach (self::getFormData() as $formDataRow) {
        //     $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
        //     $this->{$property} = $contact->{$property};
        // }

        /**
         * This part varies!
         * Updating properties visibility.
         */
        $this->technicalPassword = '';
    }

    public function initRoleData()
    {
        $rolesOfCustomer = $this->contact->roles()->get();
        foreach ($rolesOfCustomer as $roleOfCustomer) {
            if ($roleOfCustomer instanceof Role) {
                $attributes = $roleOfCustomer->getAttributes();
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
         * This part is special in this case, because out Contact model contains an additional model: ContactProfile,
         * which actually contains all the personal data.
         * If you want a general method for store(), look for a general method, like the one in the RoleEditForm component.
         */
        // $properties = [];
        $contactProfile = $this->contact->getContactProfile();
        if (! $contactProfile && ! $this->contact->id) {
            $this->contact->save();
            $this->contact->refresh();
            $contactProfile = new ContactProfile;
            $contactProfile->contact()->associate($this->contact);
            // $this->contact->contactProfile()->associate($contactProfile);
            // $this->contact->contactProfile()->save($contactProfile);
            $contactProfile->save();
            $this->contact->refresh();
            $contactProfile->refresh();
            // $this->contact->contactProfile()->refresh();
        }

        if ($contactProfile) {
            $contactProfile->firstname = $this->firstname;
            $contactProfile->lastname = $this->lastname;
            $contactProfile->email = $this->email;
            $contactProfile->phone = $this->phone;
            $contactProfile->mobile = $this->mobile;

            // dump($contactProfile);exit;
            $contactProfile->save();
            $this->contact->refresh();
            // dump($this->firstname);
            // dump($contactProfile);exit;
            // if (! $this->contact->contactprofile_id) {
            //     $this->contact->contactprofile_id = $contactProfile->id;
            // }
        }
        /*
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            if (! defined('self::TECHNICAL_PROPERTIES') || (defined('self::TECHNICAL_PROPERTIES') && ! in_array($property, self::TECHNICAL_PROPERTIES))) {
                $this->contact->{$property} = $this->{$property};
                $properties[] = $property;
            }
        }*/

        /**
         * This part varies!
         * Put here the customization of settings the entity-object values!
         */
        if (! empty($this->technicalPassword)) {
            $this->contact->password = Hash::make($this->technicalPassword);
        }

        /**
         * This property varies!
         */
        $this->contact->saveWithRoleData($this->role_data);
    }

    public function recalculateRoleData()
    {
        $roles = Role::customerRoles()->get();
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
