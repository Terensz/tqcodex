<?php

namespace Domain\Project\Livewire\Forms;

use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\ContactProfileOrganization;
use Domain\Customer\Models\Organization;
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

    public ?Organization $organization;

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
        'Organization' => [
            'organization_name' => 'name',
            'organization_long_name' => 'long_name',
            'organization_org_type' => 'org_type',
            'organization_email' => 'email',
            'organization_phone' => 'phone',
            'organization_taxpayer_id' => 'taxpayer_id',
            'organization_taxid' => 'taxid',
            'organization_eutaxid' => 'eutaxid',
            'organization_vat_banned' => 'vat_banned',
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

    // public $invitedRegister;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_name;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_long_name;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_org_type;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_taxpayer_id;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_taxid;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_eutaxid;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_email;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_phone;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_vat_banned;

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
            'organization_name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationName',
            ],
            'organization_long_name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_long_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationLongName',
            ],
            'organization_org_type' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_org_type',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrgType',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => CorporateForm::asSelectArray(),
            ],
            'organization_email' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_email',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationEmail',
            ],
            'organization_phone' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_phone',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationPhone',
            ],
            'organization_taxpayer_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_taxpayer_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationTaxpayerId',
            ],
            'organization_taxid' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_taxid',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationTaxid',
            ],
            'organization_eutaxid' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_eutaxid',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationEutaxid',
            ],
            'organization_vat_banned' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_vat_banned',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.VatBanned',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => TrueOrFalse::asSelectArray(),
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

    public function setOrganization(Organization $organization)
    {
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->organization = $organization;
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

            $this->organization->save();
            $this->organization = $this->organization->refresh();

            $contactProfileOrganization = null;
            if ($this->organization->id && $this->contactProfile->id) {
                $contactProfileOrganization = ContactProfileOrganization::where(['organization_id' => $this->organization->id, 'contact_profile_id' => $this->contactProfile->id])->first();
            }

            if (! $contactProfileOrganization) {
                $contactProfileOrganization = new ContactProfileOrganization;
                $contactProfileOrganization->contact_profile_id = $this->contactProfile->id;
                $contactProfileOrganization->organization_id = $this->organization->id;
                $contactProfileOrganization->save();
            }

            if ($this->organization_name && $this->organization_email) {
                $org = new Organization([
                    'name' => $this->organization_name,
                    'long_name' => $this->organization_long_name,
                    'email' => $this->organization_email,
                ]);

            }

            /**
             * Keep this!
             * Modify entity name on 1 place.
             */
            // $properties = [];
            // foreach (self::getFormData() as $formDataRow) {
            //     $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            //     if (! defined('self::TECHNICAL_PROPERTIES') || (defined('self::TECHNICAL_PROPERTIES') && ! in_array($property, self::TECHNICAL_PROPERTIES))) {
            //         $this->contact->{$property} = $this->{$property};
            //         $properties[] = $property;
            //     }
            // }

            // if (! $this->contact->getContact()) {
            //     $contact = new Contact();
            //     $contact->save();
            //     $contact->refresh();

            //     $this->contactProfile->setContact($contact);
            // }

            // $this->contactProfile->save();

            // return redirect()->route('customer.contact.register-successful');
            //  view('customer.contact.register-successful');
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

        foreach ($this->modelProperties['Organization'] as $formProperty => $modelProperty) {
            $this->organization->$modelProperty = $this->$formProperty;
        }
    }
}
