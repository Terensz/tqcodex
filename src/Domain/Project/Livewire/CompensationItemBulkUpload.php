<?php

namespace Domain\Project\Livewire;

use Domain\Customer\Enums\Location;
use Domain\Finance\Models\CompensationItem;
use Domain\Finance\Rules\CompensationItemBulkUploadRules;
use Domain\Project\Livewire\Forms\CompensationItemEditForm;
use Domain\Shared\Helpers\TextHelper;
use Domain\Shared\Livewire\Base\BaseBulkUploadComponent;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\User\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

// use Illuminate\Support\Facades\Storage;

final class CompensationItemBulkUpload extends BaseBulkUploadComponent
{
    public $data = [];

    public $inviteEmailBody = '';

    public $editingInviteEmail = false;

    public $uniquePropertiesToCollect = [
        'partner_email' => [
            'pairedProperty' => [
                'organizationName',
                'partner_name',
                'partner_contact',
            ],
        ],
    ];

    /**
     * The save formData which is used by the New or Edit forms.
     */
    public $formData = [];

    public $automaticLabelTransformations = [
        'customer.OrganizationName' => 'customer.OrganizationName',
        'customer.CustomerEmailAddress' => 'customer.CustomerEmailAddress',
    ];

    // public $language;

    public $jobDone = false;

    public static function getComponentName(): string
    {
        return 'compensation-item-bulk-upload';
    }

    public function submitForm()
    {
        try {
            $this
                ->prepareData()
                ->processData();

            if (isset($this->data['stats']['errors']) && $this->data['stats']['errors']['totalErrorsCount'] === 0) {
                $this->data['mail']['inviteEmailBody'] = $this->inviteEmailBody;
                \Domain\Project\Jobs\ProcessSaveBulkCompItemJob::dispatch($this->data);

                $this->jobDone = true;

                session()->flash('message', 'CSV feldolgozása elindult.');
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $this->render();
    }

    public function deleteRow($rowIndexToDelete)
    {
        $tableBodyData = [];
        foreach ($this->data['rawTableBodyData'] as $rowIndex => $rawTableBodyDataRow) {
            if ($rowIndex != $rowIndexToDelete) {
                $tableBodyData[] = $rawTableBodyDataRow;
            }
        }
        $this->data['rawTableBodyData'] = $tableBodyData;

        return $this->reprocessDataAndRender();
    }

    public function selectUnidentifiedDataLabel($dataLabelIndex, $property)
    {
        foreach ($this->formData as $formDataRow) {
            if ($formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY] === $property) {
                // $formDataRow = $this->formData[$property];
                $propertyTranslationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
                $this->data['processedLabels'][$dataLabelIndex]['identifiedProperty'] = $property;
                $this->data['processedLabels'][$dataLabelIndex]['identifiedTranslatedLabel'] = Lang::get($propertyTranslationReference, [], $this->data['language']);
            }
        }

        return $this->reprocessDataAndRenderWithoutPreparation();
    }

    /**
     * This is a lifecycle method.
     */
    // public function mount()
    // {
    //     $this->formData = self::extractOfferableFormData(CompensationItemEditForm::getFormData());
    // }

    /**
     * This is a lifecycle method.
     */
    public function mount(Request $request)
    {
        $currentContactProfile = UserService::getContactProfile();
        if (! $currentContactProfile) {
            throw new Exception('No contact logged in!');
        }

        $this->resetData();
    }

    public function resetData(): CompensationItemBulkUpload
    {
        $this->data = [];
        $this->inviteEmailBody = '';
        $this->editingInviteEmail = false;
        $this->formData = [];

        $currentContactProfile = UserService::getContactProfile();
        $this->data['current_contact'] = UserService::getContact();
        $this->data['mail']['senderAddress'] = $currentContactProfile->email;
        $this->data['mail']['senderName'] = $currentContactProfile->getName();
        $this->data['mail']['inviteEmailSubject'] = __('project.InviteEmailSubject');
        $this->inviteEmailBody = TextHelper::convert(view('emails.project.text.partner-invite')->render(), TextHelper::FORMAT_MARKDOWN, TextHelper::FORMAT_HTML);
        $this->formData = self::extractOfferableFormData(CompensationItemEditForm::getFormData());

        return $this;
    }

    public function refreshForm()
    {
        foreach ($this->data['sortedInputData'] as $rowIndex => $sortedInputDataRow) {
            $this->data['rawTableBodyData'][$rowIndex] = $sortedInputDataRow;
        }

        $this->reprocessDataAndRenderWithoutPreparation();
    }

    public function editInviteEmail()
    {
        $this->editingInviteEmail = true;

        return $this->render();
    }

    public function cancelEditInviteEmail()
    {
        $this->editingInviteEmail = false;

        return $this->render();
    }

    public function submitEditInviteEmail($text)
    {
        $this->editingInviteEmail = false;
        $this->inviteEmailBody = $text;

        return $this->render();
    }

    /**
     * This is a lifecycle method.
     */
    public function updatedFile(): CompensationItemBulkUpload
    {
        $this
            ->resetData()
            ->processFile()
            ->prepareData()
            ->processData();

        return $this;

    }

    public function reprocessDataAndRender(): CompensationItemBulkUpload
    {
        $this
            ->prepareData()
            ->processData()
            ->render();

        return $this;
    }

    public function reprocessDataAndRenderWithoutPreparation(): CompensationItemBulkUpload
    {
        $this
            ->processData()
            ->render();

        return $this;
    }

    public function prepareData(): CompensationItemBulkUpload
    {
        $this
            ->setBodyColumnStats()
            ->setLabelColumnStats()
            ->setLanguage()
            ->processLabels();

        return $this;
    }

    public function processData(): CompensationItemBulkUpload
    {
        $this
            ->setValidationAdditionalData()
            // ->setFormalErrors()
            ->prepareProcessingErrors()
            ->checkFormalErrorUnrecognizableLanguage()
            ->checkFormalErrorNoLabelRow()
            ->checkFormalErrorValueRowsHavingLessColumns()
            ->checkFormalErrorValueRowsHavingExtraColumns()
            ->checkFormalErrorMismatchingColumnCountRows()
            ->checkFormalErrorUnidentifiedDataLabels();

        $partner_location_index = null;
        foreach ($this->data['processedLabels'] as $labelIndex => $label) {
            if ($label['identifiedProperty'] == 'partner_location') {
                $partner_location_index = $labelIndex;
            }
        }
        $hasPartnerLocationHU = false;
        $hasPartnerLocationEU = false;
        foreach ($this->data['rawTableBodyData'] as $rowIndex => $rawTableBodyDataRow) {
            foreach ($rawTableBodyDataRow as $colIndex => $rawCellData) {
                if ($colIndex == $partner_location_index) {
                    if ($rawCellData == Location::HU->value) {
                        $hasPartnerLocationHU = true;
                    }
                    if ($rawCellData == Location::EU->value) {
                        $hasPartnerLocationEU = true;
                        // dump('we have Location::EU.');
                    }
                }
            }
        }

        $forceRequiredLabels = ['organizationName', 'partner_location'];

        if ($hasPartnerLocationHU) {
            $forceRequiredLabels = array_merge($forceRequiredLabels, ['partner_taxpayer_id']);
        }
        if ($hasPartnerLocationEU) {
            $forceRequiredLabels = array_merge($forceRequiredLabels, ['partner_eutaxid']);
        }

        // dump($this->data['processedLabels']);
        // dump($this->data['rawTableBodyData']);
        // dump($forceRequiredLabels);//exit;

        $this
            /**
             * This argument is a set of forced requirements for properties, now we force 'organizationName' to be required.
             * We want the uploader person to put 'organizationName' (in a translated form) to the csv header, so we force him to do so.
             */
            ->checkFormalErrorMissingRequiredColumns($forceRequiredLabels)
            ->sortFormalErrorsByCriticalness();

        if ($this->data['stats']['errors']['criticalFormalErrorsCount'] == 0) {
            $this
                ->setIdentifiedProperties()
                ->setUnidentifiedLabels()
                ->setUnusedFormData()
                ->createAssociatedTableBodyData()
                ->createModelData()
                ->createSortedInputData()
                ->validateModelData()
                ->collectUniqueProperties();
        }

        return $this;
    }

    /**
     * Notice that this is a child's version of this method.
     * The original in the parent class does not fill empty contact_id.
     */
    public function createModelData(): CompensationItemBulkUpload
    {
        $models = [];
        $extractedModelData = [];
        $stringModelAssignatures = [];
        foreach ($this->data['associatedTableBodyData'] as $rowIndex => $rawModelDataRow) {
            $model = $this->createModel($rawModelDataRow);
            /**
             * If no Contact data (id or email) was given in the csv, we will fill the contact_id with the current Contact.
             */
            if (! $model->contact_id) {
                $model->contact_id = UserService::getContact()->id;
            }
            $models[$rowIndex] = $model;
            if (method_exists($model, 'getStringModelAssignatures')) {
                $stringModelAssignatures[$rowIndex] = $model->getStringModelAssignatures();
            }
            $extractedModelDataRow = $model->toArray();
            $extractedModelData[$rowIndex] = $extractedModelDataRow;
        }

        $this->data['models'] = $models;
        $this->data['extractedModelData'] = $extractedModelData;
        $this->data['stringModelAssignatures'] = $stringModelAssignatures;

        return $this;
    }

    public function createModel(array $tableBodyRowData): object
    {
        $model = new CompensationItem($tableBodyRowData);

        return $model;
    }

    public function getModelRules($validationAdditionalData = [])
    {
        return CompensationItemBulkUploadRules::rules($this->validationAdditionalData);
    }

    public function setValidationAdditionalData(): CompensationItemBulkUpload
    {
        $collectionOf_invoice_id_for_compensation = [];
        $indexOf_invoice_id_for_compensation = null;
        foreach ($this->data['processedLabels'] as $labelIndex => $label) {
            if ($label['identifiedProperty'] === 'invoice_id_for_compensation') {
                $indexOf_invoice_id_for_compensation = $labelIndex;
            }
        }

        if ($indexOf_invoice_id_for_compensation !== null) {
            foreach ($this->data['rawTableBodyData'] as $rawTableBodyDataRow) {
                $collectionOf_invoice_id_for_compensation[] = $rawTableBodyDataRow[$indexOf_invoice_id_for_compensation];
            }
        }

        // return $returnArray;
        $this->validationAdditionalData = [
            'collections' => [
                'invoice_id_for_compensation' => [
                    'data' => $collectionOf_invoice_id_for_compensation,
                    'errorMessage' => Lang::get('project.InvoiceIdForCompensationMustBeUniqueInTheUploadedFile', [], $this->data['language']),
                ],
            ],
        ];

        return $this;
    }

    public function render()
    {
        $viewParams = [
            'data' => $this->data,
            // 'inviteEmailSubject' => $this->data['mail']['inviteEmailSubject'],
            'inviteEmailBody' => $this->inviteEmailBody,
            'editingInviteEmail' => $this->editingInviteEmail,
            'jobDone' => $this->jobDone,
        ];

        // try {
        //     $viewParams = $this->collectViewParams();
        // } catch (\Exception $e) {
        //     Log::error('src/Domain/Project/Livewire/CompensationItemBulkUpload.php '.$e);
        //     exit;
        // }

        return view('project.customer.compensation-item.bulk-upload-content-selector', $viewParams);
    }
}
