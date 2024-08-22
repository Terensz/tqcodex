<?php

namespace Domain\Project\Livewire\Forms;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Language\Models\Language;
use Domain\Project\Rules\ContactRegisterRules;
use Domain\Shared\Enums\TrueOrFalse;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class ContactRegisterForm extends BaseLivewireForm implements EditFormInterface
{
    /**
     * This constant is not required.
     * If exists: these properties will not updated in the entity-object.
     */
    public const TECHNICAL_PROPERTIES = [
        // 'technicalPassword',
        // 'retypedPassword'
    ];

    public $formSaved = false;

    public ?Contact $contact;

    public ?ContactProfile $contactProfile;

    public $modelProperties = [
        'Contact' => [
            'password',
        ],
        'ContactProfile' => [
            'title',
            'firstname',
            'middlename',
            'lastname',
            'position',
            'language',
            'email',
            'phone',
            'mobile',
            'social_media',
        ],
    ];

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $title = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $firstname = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $middlename = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $lastname = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $password = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $position = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $language = Language::LANGUAGE_HUNGARIAN;

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
    public $social_media = '';

    public function getModelRules()
    {
        return ContactRegisterRules::rules();
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        // dump(CorporateForm::asSelectArray());exit;
        // dump(TrueOrFalse::asSelectArray());exit;
        $return = [
            'title' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'title',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.NameTitle',
            ],
            'firstname' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'firstname',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Firstname',
            ],
            'middlename' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'middlename',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Middlename',
            ],
            'lastname' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'lastname',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Lastname',
            ],
            'password' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'password',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Password',
            ],
            'email' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'email',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Email',
            ],
            'phone' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'phone',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Phone',
            ],
            'mobile' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'mobile',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'user.Mobile',
            ],

        ];

        return $return;
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

        // dump($this->contact);
        // dump($this->contact->getContactProfileAttribute());exit;
    }

    public function setContactProfile(ContactProfile $contactProfile)
    {
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->contactProfile = $contactProfile;
    }

    /**
     * This method varies!
     */
    public function store()
    {

        /**
         * This part varies!
         * Put here the customization of settings the entity-object values!
         */

        /**
         * This property varies!
         */
        try {
            $this->loadModelProperties();

            // dump($this->contact);
            // dump($this->contactProfile);exit;
            /**
             * Keep this!
             */
            $this->validate($this->getModelRules());

            // $hashedPassword = Hash::make($this->password);
            $this->contact->password = Hash::make($this->password);
            $this->contact->save();
            $this->contact = $this->contact->refresh();

            $this->contactProfile->setContact($this->contact);
            $this->contactProfile->save();
            $this->contactProfile = $this->contactProfile->refresh();

        } catch (QueryException $e) {
            Log::error('/src/Domain/Project/Livewire/Forms/ContactRegisterForm.php '.$e);
            exit;
            // $this->dispatch('save-failed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('/src/Domain/Project/Livewire/Forms/ContactRegisterForm.php '.$e);
            exit;
        }
    }

    protected function loadModelProperties()
    {
        foreach ($this->modelProperties['Contact'] as $property) {
            $this->contact->$property = $this->$property;
        }

        foreach ($this->modelProperties['ContactProfile'] as $property) {
            $this->contactProfile->$property = $this->$property;
        }
    }
}
