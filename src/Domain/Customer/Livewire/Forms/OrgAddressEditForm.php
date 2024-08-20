<?php

namespace Domain\Customer\Livewire\Forms;

use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Rules\OrgAddressRules;
use Domain\Customer\Services\OrganizationService;
use Domain\Finance\Enums\VatCode;
use Domain\Shared\Enums\CorporateAddressType;
use Domain\Shared\Enums\StreetSuffix;
use Domain\Shared\Enums\TrueOrFalse;
use Domain\Shared\Helpers\CountryHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Domain\User\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class OrgAddressEditForm extends BaseLivewireForm implements EditFormInterface
{
    /**
     * This constant is not required.
     * If exists: these properties will not updated in the entity-object.
     */
    public const TECHNICAL_PROPERTIES = [
        // 'technicalPassword',
        // 'retypedPassword'
    ];

    /**
     * This property varies!
     * Overwrite it with the actual entity-object name.
     */
    public ?OrgAddress $orgAddress;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $country_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $title = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $type = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $main = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $address_type = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $postal_code = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $city = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $street_name = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $public_place_category = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $number = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $building = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $floor = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $door = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $address = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $lane = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $region = '';

    public function mount() {}

    public function getModelRules()
    {
        return OrgAddressRules::rules();
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(): array
    {
        // dump(CountryHelper::asSelectArray());exit;
        // dump(TrueOrFalse::asSelectArray());exit;
        $return = [
            'country_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'country_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.IsBanned',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => CountryHelper::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'title' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'title',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.Name',
            ],
            // 'address_type' => [
            //     BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'address_type',
            //     BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
            //     BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.AddressType',
            //     BaseLivewireForm::FORM_DATA_KEY_OPTIONS => CorporateAddressType::asSelectArray(),
            //     BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            // ],
            // 'address_type' => [
            //     BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'address_type',
            //     BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
            //     BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.TaxpayerId',
            // ],
            'main' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'main',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.IsMainAddress',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => TrueOrFalse::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'postal_code' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'postal_code',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.PostalCode',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => VatCode::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'city' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'city',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.City',
            ],
            'street_name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'street_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.StreetName',
            ],
            'public_place_category' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'public_place_category',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.StreetSuffix',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => StreetSuffix::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'number' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'number',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.HouseNumber',
            ],
            'building' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'building',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.Building',
            ],
            'floor' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'floor',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.Floor',
            ],
            'door' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'door',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.DoorNumber',
            ],
            // [
            //     BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'guard_name',
            //     BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
            //     BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'permission.GuardName',
            //     BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $guardNameOptions,
            // ],
        ];

        // dump(TrueOrFalse::asSelectArray());exit;
        // dump($return);exit;

        return $return;
    }

    /**
     * This method varies!
     * The name also varies: set{EntityName}
     */
    public function setOrgAddress(OrgAddress $orgAddress)
    {
        // dump($compensationItem);exit;
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->orgAddress = $orgAddress;
        // dump($this->orgAddress);exit;

        $this->organization_id = $orgAddress->organization_id;

        /**
         * Keep this!
         */
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
            $this->{$property} = $orgAddress->{$property};
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
        // dump($this);exit;
        /**
         * Keep this!
         */
        // dump($this->getModelRules());exit;

        // $this->validate($this->getModelRules());
        // /**
        //  * Keep this!
        //  * Modify entity name on 1 place.
        //  */
        // $properties = [];
        // foreach (self::getFormData() as $formDataRow) {
        //     $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
        //     if (! defined('self::TECHNICAL_PROPERTIES') || (defined('self::TECHNICAL_PROPERTIES') && ! in_array($property, self::TECHNICAL_PROPERTIES))) {
        //         $this->orgAddress->{$property} = $this->{$property};
        //         $properties[] = $property;
        //     }
        // }

        // dump($this);exit;

        /**
         * This part varies!
         * Put here the customization of settings the entity-object values!
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
                $this->orgAddress->{$property} = $this->{$property};
                $properties[] = $property;
            }
        }

        /**
         * This property varies!
         */
        try {
            $this->orgAddress->save();
            // $organizationBindings = OrganizationService::getOrganizationBindings($this->orgAddress->getOrganization());
            // if (! $organizationBindings['currentContactBinds']) {
            //     $this->orgAddress->contactProfiles()->sync(UserService::getContact()->getContactProfile());
            // }
        } catch (QueryException $e) {
            Log::error('src/Domain/Customer/Livewire/Forms/OrgAddressEditForm.php '.$e);
            exit;
            // $this->dispatch('save-failed');
        }
    }
}
