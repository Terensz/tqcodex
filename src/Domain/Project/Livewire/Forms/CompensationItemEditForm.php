<?php

namespace Domain\Project\Livewire\Forms;

use Domain\Customer\Enums\Location;
use Domain\Customer\Services\CustomerService;
use Domain\Finance\Enums\Currency;
use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Enums\PaymentMode;
use Domain\Finance\Models\CompensationItem;
use Domain\Finance\Rules\CompensationItemRules;
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
class CompensationItemEditForm extends BaseLivewireForm implements EditFormInterface
{
    /**
     * This constant is not required.
     * If exists: these properties will not updated in the entity-object.
     */
    public const TECHNICAL_PROPERTIES = [
        'contactEmail',
        'organizationName',
    ];

    /**
     * This property varies!
     * Overwrite it with the actual entity-object name.
     */
    public ?CompensationItem $compensationItem;

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $contact_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $organization_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $invoice_id_for_compensation = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $invoice_internal_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $due_date = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $invoice_date = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $fulfilment_date = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $late_interest_rate = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $late_interest_amount = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $invoice_amount = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $invoice_type = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $payment_mode = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $currency = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $description = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $is_part_amount = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $is_disputed = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public ?int $partner_org_id = null;

    public $partner_org_string = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_location = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_name = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_taxpayer_id = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_eutaxid = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_taxid = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_address = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_email = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_phone = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $partner_contact = '';

    /**
     * This property varies!
     * Livewire form classes get all the properties of their primary entity.
     */
    public $is_compensed = '';

    public function getModelRules()
    {
        return CompensationItemRules::rules($this->compensationItem);
    }

    /**
     * This method varies!
     * Change the array as it will represent the actial entity-object's properties.
     */
    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        $organizationOptions = [];
        $organizationOptions[] = [
            ValidationHelper::OPTION_VALUE => null,
            ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
        ];
        foreach (CustomerService::getOrganizations() as $organization) {
            $organizationOptions[] = [
                ValidationHelper::OPTION_VALUE => $organization->id,
                ValidationHelper::OPTION_LABEL => $organization->name,
            ];
        }

        return [
            'contactEmail' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'contactEmail',
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.CustomerEmailAddress',
            ],
            'organizationName' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organizationName',
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.OrganizationName',
            ],
            'contact_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'contact_id',
                BaseLivewireForm::OFFERABLE_AS_BULK_LABEL => false,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.Customer',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => [
                    [
                        ValidationHelper::OPTION_VALUE => null,
                        ValidationHelper::OPTION_LABEL => __('shared.PleaseChoose'),
                    ],
                    [
                        ValidationHelper::OPTION_VALUE => UserService::getContact()->id,
                        ValidationHelper::OPTION_LABEL => UserService::getContact()->name,
                    ],
                ],
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'organization_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_id',
                BaseLivewireForm::OFFERABLE_AS_BULK_LABEL => false,
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.Organization',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $organizationOptions,
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'invoice_id_for_compensation' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'invoice_id_for_compensation',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.InvoiceIdForCompensation',
            ],
            'invoice_internal_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'invoice_internal_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.InvoiceInternalId',
            ],
            'due_date' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'due_date',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_DATEPICKER,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.DueDate',
            ],
            'invoice_date' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'invoice_date',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_DATEPICKER,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.InvoiceDate',
            ],
            'fulfilment_date' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'fulfilment_date',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_DATEPICKER,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.FulfilmentDate',
            ],
            'late_interest_rate' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'late_interest_rate',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.LateInterestRate',
            ],
            'late_interest_amount' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'late_interest_amount',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.LateInterestAmount',
            ],
            'invoice_amount' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'invoice_amount',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.InvoiceAmount',
            ],
            'invoice_type' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'invoice_type',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.InvoiceType',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => InvoiceType::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'payment_mode' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'payment_mode',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PaymentMode',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => PaymentMode::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'currency' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'currency',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.Currency',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => Currency::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'description' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'description',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXTAREA,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'shared.Description',
            ],
            'is_part_amount' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'is_part_amount',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.IsPartAmount',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => TrueOrFalse::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'is_disputed' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'is_disputed',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.IsDisputed',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => TrueOrFalse::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'partner_org_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_org_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerOrg',
                BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY => true,
            ],
            // 'partner_org_id' => [
            //     BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'organization_id',
            //     BaseLivewireForm::OFFERABLE_AS_BULK_LABEL => false,
            //     BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
            //     BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'customer.Organization',
            //     BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $organizationOptions,
            //     BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            // ],
            'partner_location' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_location',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerLocation',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => Location::asSelectArray(),
                BaseLivewireForm::FORM_DATA_KEY_DEFER => false,
            ],
            'partner_name' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerName',
            ],
            'partner_taxpayer_id' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_taxpayer_id',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerTaxpayerId',
            ],
            'partner_eutaxid' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_eutaxid',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerEUTaxId',
            ],
            'partner_address' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_address',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerAddress',
            ],
            'partner_email' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_email',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerEmail',
            ],
            'partner_phone' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_phone',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerPhone',
            ],
            'partner_contact' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'partner_contact',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.PartnerContact',
            ],
            'is_compensed' => [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'is_compensed',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'finance.IsCompensed',
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
    }

    /**
     * This method varies!
     * The name also varies: set{EntityName}
     */
    public function setCompensationItem(CompensationItem $compensationItem)
    {
        // dump($compensationItem);exit;
        /**
         * This property and variable vary. Change them according to the form's primary entity.
         */
        $this->compensationItem = $compensationItem;

        /**
         * Keep this!
         */
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[self::FORM_DATA_KEY_PROPERTY];
            $this->{$property} = $compensationItem->{$property};
        }

        // dump(InvoiceType::CLAIM->label());exit;
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
         * Modify entity name on 1 place.
         */
        $properties = [];
        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            if (! defined('self::TECHNICAL_PROPERTIES') || (defined('self::TECHNICAL_PROPERTIES') && ! in_array($property, self::TECHNICAL_PROPERTIES))) {
                // dump('===========',$property, $this->{$property});
                // dump('------');
                $this->compensationItem->{$property} = $this->{$property};
                $properties[] = $property;

                // if ($property === 'partner_org_id') {
                //     dump($this->{$property});
                //     dump($this->compensationItem);//exit;
                // }
            }
        }

        // dump($this->getModelRules());exit;

        /**
         * Keep this!
         */
        $this->validate($this->getModelRules());

        // dump($this->compensationItem);exit;

        // $this->compensationItem->partner_org_id = null;
        // dump($validation);
        // dump($properties);
        // dump($this->compensationItem);

        try {
            $this->compensationItem->save();
        } catch (QueryException $e) {
            Log::error('src/Domain/Project/Livewire/Forms/CompensationItemEditForm.php '.$e);
            exit;
        }
        // catch (\Exception $e) {
        //     dump($e);
        //     exit;
        // }
    }
}
