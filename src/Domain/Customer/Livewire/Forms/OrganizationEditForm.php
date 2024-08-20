<?php

namespace Domain\Customer\Livewire\Forms;

use Domain\Customer\Enums\CorporateForm;
use Domain\Customer\Enums\Location;
use Domain\Customer\Models\Organization;
use Domain\Customer\Rules\OrganizationRules;
use Domain\Customer\Services\OrganizationService;
use Domain\Finance\Enums\VatCode;
use Domain\Shared\Enums\CorporateAddressType;
use Domain\Shared\Enums\TrueOrFalse;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * This is a Livewire form.
 * For more info, check Livewire documentation: "Forms / Extracting a form object".
 */
class OrganizationEditForm extends BaseLivewireForm implements EditFormInterface
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
    public ?Organization $organization;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public ?int $is_banned;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $name = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $long_name = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $taxpayer_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $vat_code = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $county_code = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $vat_verified_at = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public ?int $vat_banned;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $org_type = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $address_type = CorporateAddressType::HEADQUARTERS->value;

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
    public $country_code = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $eutaxid = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $taxid = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $location = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $social_media = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $map_coordinates = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $description = '';

    public function getModelRules()
    {
        return OrganizationRules::rules();
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        // dump(TrueOrFalse::asSelectArray());exit;
        $return = [
            'is_banned' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'is_banned',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.IsBanned',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => TrueOrFalse::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.Name',
            ],
            'long_name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'long_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.LongName',
            ],
            'location' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'location',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.Location',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => Location::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'taxpayer_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'taxpayer_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.TaxpayerId',
            ],
            'vat_code' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'vat_code',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.VatCode',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => VatCode::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'org_type' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'org_type',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrgType',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => CorporateForm::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
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
            'vat_banned' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'vat_banned',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.VatBanned',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => TrueOrFalse::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
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
    public function setOrganization(Organization $organization)
    {
        // dump($compensationItem);exit;
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->organization = $organization;

        /**
         * Keep this!
         */
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
            $this->{$property} = $organization->{$property};
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
        $this->validate($this->getModelRules());
        /**
         * Keep this!
         * Modify entity name on 1 place.
         */
        $properties = [];
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            if (! defined('self::TECHNICAL_PROPERTIES') || (defined('self::TECHNICAL_PROPERTIES') && ! in_array($property, self::TECHNICAL_PROPERTIES))) {
                $this->organization->{$property} = $this->{$property};
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
        try {
            $this->organization->save();
            $organizationBindings = OrganizationService::getOrganizationBindings($this->organization);
            if (! $organizationBindings['currentContactBinds']) {
                $this->organization->contactProfiles()->sync(UserService::getContact()->getContactProfile());
            }
        } catch (QueryException $e) {
            Log::error('src/Domain/Customer/Livewire/Forms/OrgAddressEditForm.php '.$e);
            exit;
            // $this->dispatch('save-failed');
        }
    }
}
