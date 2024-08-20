<?php

namespace Domain\Shared\Livewire\Base;

use Domain\Shared\Helpers\PHPHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

/**
 * Tips:
 * - Formal errors have a "critical" property. If a formal error is critical, than the table will not be displayed.
 */
abstract class BaseBulkUploadComponent extends BaseLivewireComponent
{
    use WithFileUploads;

    /*
    $data - Structure example:
    ==========================
    "stats" => [
        "maxColumnsCount" => 18
        "maxBodyColumnsCount" => 18
        "bodyColumnsCount" => array:1 [
            18 => array:9 [
                0 => 0
                1 => 1
                2 => 2
                3 => 3
                4 => 4
                5 => 5
                6 => 6
                7 => 7
                8 => 8
            ]
        ]
        "labelColumnsCount" => 18
        "errors" => array:7 [
            "totalErrorsCount" => 1
            "totalFormalErrorsCount" => 0
            "totalValidationErrorsCount" => 1
            "criticalFormalErrorsCount" => 0
            "nonCriticalFormalErrorsCount" => 0
            "criticalFormalErrorTypesCount" => 0
            "nonCriticalFormalErrorTypesCount" => 0
        ]
    ],
    "errors" => [
        "formalErrors" => array:7 [
            "unrecognizableLanguage" => array:6 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => true
                "type" => "boolean"
                "value" => null
                "errorMessageTranslationReference" => "shared.UnrecognizableLanguage"
            ]
            "noLabelRow" => array:6 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => true
                "type" => "boolean"
                "value" => null
                "errorMessageTranslationReference" => "shared.NoLabelRow"
            ]
            "valueRowsHavingLessColumns" => array:6 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => true
                "type" => "collection"
                "valueArray" => []
                "errorMessageTranslationReference" => "shared.ValueRowsHavingLessColumns"
            ]
            "valueRowsHavingExtraColumns" => array:6 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => false
                "type" => "collection"
                "valueArray" => []
                "errorMessageTranslationReference" => "shared.ValueRowsHavingExtraColumns"
            ]
            "mismatchingColumnCountRows" => array:6 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => false
                "type" => "collection"
                "valueArray" => []
                "errorMessageTranslationReference" => "shared.ValueRowsHavingExtraColumns"
            ]
            "unidentifiedDataLabels" => array:7 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => false
                "type" => "collection"
                "valueArray" => []
                "errorMessageTranslationReference" => "shared.UnidentifiedDataLabels"
                "errorMessageTranslationParams" => array:1 [
                    0 => array:1 [
                        "key" => "data_labels"
                    ]
                ]
            ]
            "missingRequiredColumns" => array:7 [
                "triggered" => false
                "errorsCount" => 0
                "critical" => false
                "type" => "collection"
                "valueArray" => []
                "errorMessageTranslationReference" => "shared.MissingRequiredColumns"
                "errorMessageTranslationParams" => array:1 [
                    0 => array:1 [
                        "key" => "columns"
                    ]
                ]
            ]
        ]
        "classifiedTriggeredFormalErrors" => array:2 [
            "critical" => []
            "nonCritical" => []
        ]
        "validationErrors" => array:9 [
            0 => []
            1 => []
            2 => []
            3 => []
            4 => array:1 [
            "partner_email" => array:1 [
                0 => "A(z) partner email mező kötelező, ha a többi feltétel hiányzik: Partner szervezet."
            ]
            ]
            5 => []
            6 => []
            7 => []
            8 => []
        ]
        "sortedValidationErrors" => array:2 [
            "visible" => array:1 [
                4 => array:1 [
                    "partner_email" => array:1 [
                        0 => "A(z) partner email mező kötelező, ha a többi feltétel hiányzik: Partner szervezet."
                    ]
                ]
            ]
            "invisible" => []
        ]
    ],
    */
    public $data = [];

    public $formData = [];

    public $automaticLabelTransformations = [];

    public $uniquePropertiesToCollect = [];

    // public $collectedUniqueProperties = [];

    public $validationAdditionalData = [
        'collections' => [],
        // 'translationParams' => [],
    ];

    const ERROR_TYPE_FORMAL = 'Formal';

    const ERROR_TYPE_VALIDATION = 'Validation';

    // public $language;

    // public $dataLabels;

    #[Validate('file|max:1024')] // 1MB Max
    public $file;

    public function addErrorsCount($type, $count)
    {
        if ($type == self::ERROR_TYPE_FORMAL) {
            $this->data['stats']['errors']['totalFormalErrorsCount'] += $count;
        }
        if ($type == self::ERROR_TYPE_VALIDATION) {
            $this->data['stats']['errors']['totalValidationErrorsCount'] += $count;
        }

        $this->data['stats']['errors']['totalErrorsCount'] += $count;
    }

    /**
     * Collecting the offerable elements of the formData.
     */
    public static function extractOfferableFormData($formData): array
    {
        $offerableFormData = [];
        foreach ($formData as $formDataIndex => $formDataRow) {
            if (! array_key_exists(BaseLivewireForm::OFFERABLE_AS_BULK_LABEL, $formDataRow)) {
                /**
                 * If OFFERABLE_AS_BULK_LABEL key is not set, than we use the default. (Logically it's true by default, but there can be a special situation.)
                 */
                $formDataRow[BaseLivewireForm::OFFERABLE_AS_BULK_LABEL] = BaseLivewireForm::DEFAULT_OFFERABLE_AS_BULK_LABEL;
            }

            if ($formDataRow[BaseLivewireForm::OFFERABLE_AS_BULK_LABEL]) {
                $offerableFormData[$formDataIndex] = $formDataRow;
            }
        }

        return $offerableFormData;
    }

    public static function getLanguages(): \Illuminate\Support\Collection
    {
        $languages = collect(File::directories(base_path('lang')))->map(function ($path) {
            return basename($path);
        });

        return $languages;
    }

    /**
     * Extracting the table data out of the uploaded file.
     */
    public function processFile(): BaseBulkUploadComponent
    {
        $path = $this->file->getRealPath();
        $contents = file_get_contents($path);
        $rows = explode("\n", $contents);
        $tableLabelData = [];
        $tableBodyData = [];
        $rowIndex = 0;

        foreach ($rows as $row) {
            $rowData = str_getcsv($row, ';');

            /**
             * Label row
             */
            if ($rowIndex === 0) {
                $tableLabelData = $rowData;
            }
            /**
             * Value row
             */
            elseif (! (count($rowData) === 1 && empty($rowData[0]))) {
                $tableBodyData[] = $rowData;
            }

            $rowIndex++;
        }

        $this->data['rawTableLabelData'] = $tableLabelData;
        $this->data['rawTableBodyData'] = $tableBodyData;

        return $this;
    }

    public function prepareData(): BaseBulkUploadComponent
    {
        return $this;
    }

    public function processData(): BaseBulkUploadComponent
    {
        return $this;
    }

    /**
     * Setting:
     * > $this->data['maxColumnsCount']
     * > $this->data['bodyColumnsCount']
     */
    public function setBodyColumnStats(): BaseBulkUploadComponent
    {
        $bodyColumnsCount = [];
        $maxColumnsCount = 0;

        foreach ($this->data['rawTableBodyData'] as $rowIndex => $rowData) {
            $columnsCount = count($rowData);
            if (! isset($bodyColumnsCount[$columnsCount])) {
                $bodyColumnsCount[$columnsCount] = [];
            }

            $bodyColumnsCount[$columnsCount][] = $rowIndex;
            if ($maxColumnsCount < $columnsCount) {
                $maxColumnsCount = $columnsCount;
            }
        }

        $this->data['stats']['maxColumnsCount'] = $maxColumnsCount;
        $this->data['stats']['maxBodyColumnsCount'] = $maxColumnsCount;
        $this->data['stats']['bodyColumnsCount'] = $bodyColumnsCount;

        return $this;
    }

    /**
     * > Updating: $this->data['maxColumnsCount']
     * > Setting: $this->data['labelColumnsCount']
     */
    public function setLabelColumnStats(): BaseBulkUploadComponent
    {
        $this->data['stats']['maxColumnsCount'] = count($this->data['rawTableLabelData']) > $this->data['stats']['maxColumnsCount'] ? count($this->data['rawTableLabelData']) : $this->data['stats']['maxColumnsCount'];
        $this->data['stats']['labelColumnsCount'] = count($this->data['rawTableLabelData']);

        return $this;
    }

    /**
     * Detecting the language of the csv file.
     */
    public function setLanguage(): BaseBulkUploadComponent
    {
        $languages = self::getLanguages();
        $foundLanguages = [];
        foreach ($this->formData as $formDataRow) {
            foreach ($this->data['rawTableLabelData'] as $label) {
                foreach ($languages as $language) {
                    $translationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
                    $translatedLabel = Lang::get($translationReference, [], $language);
                    if (! empty($translatedLabel) && ! empty($label) && $translatedLabel === $label) {
                        $foundLanguages[] = $language;
                    }
                }
            }
        }

        /**
         * Most likely we will return here.
         * But if the text was the same in two or more languages, than we will find out which was the most hit.
         */
        if (count($foundLanguages) === 1) {
            $this->data['language'] = $foundLanguages[0];

            return $this;
        }

        if (count($foundLanguages) === 0) {
            $this->data['language'] = null;

            return $this;
        }

        /**
         * We should divide the hits by languages.
         */
        $foundLanguageCounter = [];
        foreach ($foundLanguages as $foundLanguage) {
            if (! isset($foundLanguageCounter[$foundLanguage])) {
                $foundLanguageCounter[$foundLanguage] = 0;
            }

            $foundLanguageCounter[$foundLanguage]++;
        }

        /**
         * And now we sould check which language has the most hits.
         */
        $mostHits = 0;
        $mostHitsLanguage = null;
        foreach ($foundLanguageCounter as $loopedLanguage => $hits) {
            if ($hits > $mostHits) {
                $mostHits = $hits;
                $mostHitsLanguage = $loopedLanguage;
            }
        }

        $this->data['language'] = $mostHitsLanguage;

        return $this;
    }

    /**
     * Now we will identify every column of the Label data we just can.
     */
    public function processLabels(): BaseBulkUploadComponent
    {
        $processedLabels = [];
        $identifiedProperties = [];
        foreach ($this->data['rawTableLabelData'] as $columnIndex => $originalLabel) {
            $labelFound = false;
            foreach ($this->formData as $formDataRow) {
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $translationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
                $translatedLabel = Lang::get($translationReference, [], $this->data['language']);
                foreach ($this->automaticLabelTransformations as $automaticLabelTransformationFromRef => $automaticLabelTransformationToRef) {
                    $automaticLabelTransformationFromLabel = Lang::get($automaticLabelTransformationFromRef, [], $this->data['language']);
                    if ($translatedLabel === $automaticLabelTransformationFromLabel) {
                        $translatedLabel = Lang::get($automaticLabelTransformationToRef, [], $this->data['language']);
                    }
                }
                if ($translatedLabel === $originalLabel) {
                    $processedLabels[$columnIndex] = [
                        'identifiedProperty' => $property,
                        'identifiedTranslatedLabel' => $translatedLabel,
                    ];
                    $identifiedProperties[] = $property;
                    $labelFound = true;
                }
            }

            if (! $labelFound) {
                $processedLabels[$columnIndex] = [
                    'identifiedProperty' => null,
                    'identifiedTranslatedLabel' => null,
                ];
            }

            $processedLabels[$columnIndex]['originalLabel'] = $originalLabel;
        }

        $this->data['identifiedProperties'] = $identifiedProperties;

        $this->data['processedLabels'] = $processedLabels;

        return $this;
    }

    /**
     * This is just an empty method in the parent class.
     * You can override it in the child class.
     */
    public function setValidationAdditionalData(): BaseBulkUploadComponent
    {
        return $this;
    }

    /**
     * Resetting the summarizations.
     */
    public function prepareProcessingErrors(): BaseBulkUploadComponent
    {
        $this->data['stats']['errors']['totalErrorsCount'] = 0;
        $this->data['stats']['errors']['totalFormalErrorsCount'] = 0;
        $this->data['stats']['errors']['totalValidationErrorsCount'] = 0;

        return $this;
    }

    /**
     * Searching a specific formal error in the uploaded data.
     */
    public function checkFormalErrorUnrecognizableLanguage(): BaseBulkUploadComponent
    {
        /**
         * No recognizable language.
         */
        $errorsCount = 0;
        if (! $this->data['language']) {
            $errorsCount = 1;
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['unrecognizableLanguage'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => true,
            'type' => 'boolean',
            'value' => null,
            'errorMessageTranslationReference' => 'shared.UnrecognizableLanguage',
        ];

        return $this;
    }

    /**
     * Searching a specific formal error in the uploaded data.
     */
    public function checkFormalErrorNoLabelRow(): BaseBulkUploadComponent
    {
        /**
         * No label row: technically: no rows at all.
         */
        $errorsCount = 0;
        if ($this->data['stats']['labelColumnsCount'] == 0) {
            $errorsCount = 1;
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['noLabelRow'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => true,
            'type' => 'boolean',
            'value' => null,
            'errorMessageTranslationReference' => 'shared.NoLabelRow',
        ];

        return $this;
    }

    /**
     * Searching a specific formal error in the uploaded data.
     *
     * Label row contains more columns than the most column containing body row.
     */
    public function checkFormalErrorValueRowsHavingLessColumns(): BaseBulkUploadComponent
    {
        $valueRowsHavingLessColumns = [];
        $errorsCount = 0;
        foreach ($this->data['stats']['bodyColumnsCount'] as $bodyColumnsCount => $affectedRowIndexes) {
            if ($bodyColumnsCount < $this->data['stats']['labelColumnsCount']) {
                $valueRowsHavingLessColumns = [
                    ...$affectedRowIndexes,
                ];
                $errorsCount += count($affectedRowIndexes);
            }
        }

        if ($errorsCount > 0) {
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['valueRowsHavingLessColumns'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => true,
            'type' => 'collection',
            'valueArray' => $valueRowsHavingLessColumns,
            'errorMessageTranslationReference' => 'shared.ValueRowsHavingLessColumns',
        ];

        return $this;
    }

    /**
     * Searching a specific formal error in the uploaded data.
     */
    public function checkFormalErrorValueRowsHavingExtraColumns()
    {
        $valueRowsHavingExtraColumns = [];
        $errorsCount = 0;
        foreach ($this->data['stats']['bodyColumnsCount'] as $bodyColumnsCount => $affectedRowIndexes) {
            if ($bodyColumnsCount > $this->data['stats']['labelColumnsCount']) {
                $valueRowsHavingExtraColumns = [
                    ...$affectedRowIndexes,
                ];
                $errorsCount += count($affectedRowIndexes);
            }
        }

        if ($errorsCount > 0) {
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['valueRowsHavingExtraColumns'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => false,
            'type' => 'collection',
            'valueArray' => $valueRowsHavingExtraColumns,
            'errorMessageTranslationReference' => 'shared.ValueRowsHavingExtraColumns',
        ];

        return $this;
    }

    public function checkFormalErrorMismatchingColumnCountRows(): BaseBulkUploadComponent
    {
        $mismatchingColumnCountRows = [];
        $errorsCount = 0;
        foreach ($this->data['stats']['bodyColumnsCount'] as $bodyColumnsCount => $affectedRowIndexes) {
            if (count(PHPHelper::arrayKeys($this->data['stats']['bodyColumnsCount'])) > 1) {
                if ($bodyColumnsCount != $this->data['stats']['labelColumnsCount']) {
                    foreach ($affectedRowIndexes as $affectedRowIndex) {
                        $mismatchingColumnCountRows[] = $affectedRowIndex;
                        $errorsCount++;
                    }
                }
            }
        }

        if ($errorsCount > 0) {
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['mismatchingColumnCountRows'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => false,
            'type' => 'collection',
            'valueArray' => $mismatchingColumnCountRows,
            'errorMessageTranslationReference' => 'shared.ValueRowsHavingExtraColumns',
        ];

        return $this;
    }

    public function checkFormalErrorUnidentifiedDataLabels(): BaseBulkUploadComponent
    {
        $errorsCount = 0;
        $unidentifiedDataLabels['valueArray'] = [];
        foreach ($this->data['processedLabels'] as $columnIndex => $processedLabel) {
            if (! $processedLabel['identifiedProperty']) {
                $unidentifiedDataLabels['valueArray'][$columnIndex] = $processedLabel['originalLabel'];
            }
        }

        if (! empty($unidentifiedDataLabels['valueArray'])) {
            $errorsCount = count(array_keys($unidentifiedDataLabels['valueArray']));
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['unidentifiedDataLabels'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => false,
            'type' => 'collection',
            'valueArray' => $unidentifiedDataLabels['valueArray'],
            'errorMessageTranslationReference' => 'shared.UnidentifiedDataLabels',
            'errorMessageTranslationParams' => [
                [
                    'key' => 'data_labels',
                ],
            ],
        ];

        return $this;
    }

    public function checkFormalErrorMissingRequiredColumns(array $forceRequired = []): BaseBulkUploadComponent
    {
        $missingRequiredColumns = $this->detectMissingRequiredColumns($forceRequired);

        $errorsCount = count($missingRequiredColumns);
        if ($errorsCount > 0) {
            $this->addErrorsCount(self::ERROR_TYPE_FORMAL, $errorsCount);
        }

        $this->data['errors']['formalErrors']['missingRequiredColumns'] = [
            'triggered' => $errorsCount > 0,
            'errorsCount' => $errorsCount,
            'critical' => false,
            'type' => 'collection',
            'valueArray' => $missingRequiredColumns,
            'errorMessageTranslationReference' => 'shared.MissingRequiredColumns',
            'errorMessageTranslationParams' => [
                [
                    'key' => 'columns',
                ],
            ],
        ];

        return $this;
    }

    /**
     * We need to collect all the identified properties from the processed label data.
     */
    public function setIdentifiedProperties(): BaseBulkUploadComponent
    {
        $identifiedProperties = [];
        foreach ($this->data['processedLabels'] as $processedLabel) {
            if ($processedLabel['identifiedProperty']) {
                $identifiedProperties[] = $processedLabel['identifiedProperty'];
            }
        }

        $this->data['identifiedProperties'] = $identifiedProperties;

        return $this;
    }

    public function setUnidentifiedLabels(): BaseBulkUploadComponent
    {
        $index = 0;
        $this->data['unidentifiedLabels'] = [];
        foreach ($this->data['processedLabels'] as $processedLabelIndex => $processedLabel) {
            if (! $processedLabel['identifiedTranslatedLabel']) {
                $this->data['unidentifiedLabels'][$index] = [
                    'processedLabelIndex' => $processedLabelIndex,
                    'property' => null,
                ];
                $index++;
            }
        }

        return $this;
    }

    /**
     * We need this on the blade to offer valid options instead of the unidentified labels.
     */
    public function setUnusedFormData(): BaseBulkUploadComponent
    {
        $unusedFormData = [];
        foreach ($this->formData as $formDataRow) {
            if (! isset($formDataRow[BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY])) {
                $formDataRow[BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY] = false;
            }
            $cannotBeOfferedAsUnusedProperty = $formDataRow[BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY];
            $propertyExistsAndNotLabelYet = isset($formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY]) && ! in_array($formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY], $this->data['identifiedProperties']);
            if ($propertyExistsAndNotLabelYet && ! $cannotBeOfferedAsUnusedProperty) {
                $unusedFormData[] = $formDataRow;
            }
        }

        $this->data['unusedFormData'] = $unusedFormData;

        return $this;
    }

    public function sortFormalErrorsByCriticalness(): BaseBulkUploadComponent
    {
        $criticalFormalErrorsCount = 0;
        $nonCriticalFormalErrorsCount = 0;
        $criticalFormalErrorTypesCount = 0;
        $nonCriticalFormalErrorTypesCount = 0;
        $this->data['errors']['classifiedTriggeredFormalErrors'] = [
            'critical' => [],
            'nonCritical' => [],
        ];

        foreach ($this->data['errors']['formalErrors'] as $errorCode => $formalError) {
            if ($formalError['triggered']) {
                if ($formalError['critical']) {
                    $this->data['errors']['classifiedTriggeredFormalErrors']['critical'][][$errorCode] = $formalError;
                    $criticalFormalErrorTypesCount++;
                    $criticalFormalErrorsCount += $formalError['errorsCount'];
                } else {
                    $this->data['errors']['classifiedTriggeredFormalErrors']['nonCritical'][][$errorCode] = $formalError;
                    $nonCriticalFormalErrorTypesCount++;
                    $nonCriticalFormalErrorsCount += $formalError['errorsCount'];
                }
            }
        }

        $this->data['stats']['errors']['criticalFormalErrorsCount'] = $criticalFormalErrorsCount;
        $this->data['stats']['errors']['nonCriticalFormalErrorsCount'] = $nonCriticalFormalErrorsCount;
        $this->data['stats']['errors']['criticalFormalErrorTypesCount'] = $criticalFormalErrorTypesCount;
        $this->data['stats']['errors']['nonCriticalFormalErrorTypesCount'] = $nonCriticalFormalErrorTypesCount;

        return $this;
    }

    public function createAssociatedTableBodyData(): BaseBulkUploadComponent
    {
        $result = [];
        foreach ($this->data['rawTableBodyData'] as $rowIndex => $tableBodyRow) {
            foreach ($tableBodyRow as $columnIndex => $cellValue) {
                if (isset($this->data['processedLabels'][$columnIndex])) {
                    $identifiedProperty = $this->data['processedLabels'][$columnIndex]['identifiedProperty'];
                    if (! empty($identifiedProperty)) {
                        $result[$rowIndex][$identifiedProperty] = $cellValue;
                    }
                }
            }
        }

        // unset($this->data['rawTableBodyData']);
        $this->data['associatedTableBodyData'] = $result;

        return $this;
    }

    /**
     * We are making an extraction of the data: loading raw data into the model, letting the model do the alterations,
     * than extracting the data with the ->toArray() method out of the model instance.
     *
     * We use this type of data while the validation process, also when collecting the input data.
     * (Input data will go to the input fields of the table.)
     */
    public function createModelData(): BaseBulkUploadComponent
    {
        $models = [];
        $extractedModelData = [];
        $stringModelAssignatures = [];
        foreach ($this->data['associatedTableBodyData'] as $rowIndex => $rawModelDataRow) {
            $model = $this->createModel($rawModelDataRow);
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

    /**
     * This data will go to the input fields of the displayed table.
     *
     * We need the data in exactly that order we received at the upload.
     * To do this, we just iterate the "processedLabels", as a guide for this sorting.
     * If we find the property in the array we extracted from the model, than we are using it, if not, we put the original value for the accurate place.
     */
    public function createSortedInputData(): BaseBulkUploadComponent
    {
        $result = [];
        foreach ($this->data['extractedModelData'] as $rowIndex => $extractedModelDataRow) {
            $columnIndex = 0;
            foreach ($this->data['processedLabels'] as $processedLabel) {
                if ($processedLabel['identifiedProperty']) {
                    // if (! isset($extractedModelDataRow[$processedLabel['identifiedProperty']])) {
                    //     dump($extractedModelDataRow);exit;
                    // }
                    $result[$rowIndex][] = $extractedModelDataRow[$processedLabel['identifiedProperty']];
                } else {
                    $result[$rowIndex][] = $this->data['rawTableBodyData'][$rowIndex][$columnIndex];
                }

                $columnIndex++;
            }
        }

        $this->data['sortedInputData'] = $result;

        return $this;
    }

    public function validateModelData(): BaseBulkUploadComponent
    {
        $validationErrors = [];
        $sortedValidationErrors = [
            'visible' => [],
            'invisible' => [],
        ];
        foreach ($this->data['extractedModelData'] as $rowIndex => $extractedModelDataRow) {
            // $model = $this->data['models'][$rowIndex];
            $rules = $this->getModelRules();

            $validator = Validator::make($this->data['extractedModelData'][$rowIndex], $rules);

            $validationErrors[$rowIndex] = [];
            if ($validator->fails()) {
                $validationErrors[$rowIndex] = $validator->errors()->toArray();
                foreach ($validationErrors[$rowIndex] as $property => $validationErrorArray) {
                    if (in_array($property, $this->data['identifiedProperties'])) {
                        $this->addErrorsCount(self::ERROR_TYPE_VALIDATION, count($validationErrorArray));
                        $sortedValidationErrors['visible'][$rowIndex][$property] = $validationErrorArray;
                    } else {
                        $sortedValidationErrors['invisible'][$rowIndex][$property] = $validationErrorArray;
                    }
                }
            }
        }

        $this->data['errors']['validationErrors'] = $validationErrors;

        $this->data['errors']['sortedValidationErrors'] = $sortedValidationErrors;

        return $this;
    }

    public function collectUniqueProperties(): BaseBulkUploadComponent
    {
        $uniquePropertiesToCollect = $this->uniquePropertiesToCollect;
        $this->data['collectedUniqueProperties'] = [];

        foreach ($this->data['extractedModelData'] as $rowIndex => $rowData) {
            if (! empty($uniquePropertiesToCollect)) {
                foreach ($uniquePropertiesToCollect as $uniqueProperty => $uniquePropertySettings) {
                    if (! isset($this->data['collectedUniqueProperties'][$uniqueProperty])) {
                        $this->data['collectedUniqueProperties'][$uniqueProperty] = [];
                    }

                    foreach ($this->data['processedLabels'] as $processedLabelIndex => $processedLabel) {
                        if (! empty($uniqueProperty) && $uniqueProperty === $processedLabel['identifiedProperty']) {
                            $uniquePropertyValue = $rowData[$uniqueProperty];

                            $valueIsAlreadyCollected = false;
                            if (! empty($this->data['collectedUniqueProperties'][$uniqueProperty])) {
                                foreach ($this->data['collectedUniqueProperties'][$uniqueProperty] as $uniquePropertyData) {
                                    if (isset($uniquePropertyData[$uniqueProperty]) && $uniquePropertyData[$uniqueProperty] === $uniquePropertyValue) {
                                        $valueIsAlreadyCollected = true;
                                    }
                                }
                            }

                            $index = count($this->data['collectedUniqueProperties'][$uniqueProperty]);

                            if (! empty($uniquePropertyValue) && ! $valueIsAlreadyCollected) {
                                $this->data['collectedUniqueProperties'][$uniqueProperty][$index][$uniqueProperty] = $uniquePropertyValue;

                                if (isset($uniquePropertySettings['pairedProperty']) && ! empty($uniquePropertySettings['pairedProperty']) && is_array($uniquePropertySettings['pairedProperty'])) {
                                    foreach ($uniquePropertySettings['pairedProperty'] as $pairedProperty) {
                                        if (isset($rowData[$pairedProperty])) {
                                            $pairedPropertyValue = $rowData[$pairedProperty];
                                            $this->data['collectedUniqueProperties'][$uniqueProperty][$index][$pairedProperty] = $pairedPropertyValue;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this;
    }

    // /**
    //  * The purpose of this method is to
    // */
    // public function sortInputValues(array $extractedModelDataRow, array $rawInputDataRow): array
    // {
    //     $arrangedInputValues = [];

    //     $index = 0;
    //     foreach ($this->data['processedLabels'] as $processedLabel) {
    //         if ($processedLabel['identifiedProperty']) {
    //             $arrangedInputValues[] = $extractedModelDataRow[$processedLabel['identifiedProperty']];
    //         } else {
    //             $arrangedInputValues[] = $rawInputDataRow[$index];
    //         }

    //         $index++;
    //     }

    //     return $arrangedInputValues;
    // }

    // public function doValidation(): BaseBulkUploadComponent
    // {
    //     $tableRowCounter = 0;
    //     foreach ($this->data['associatedTableBodyData'] as $rowData) {
    //         $stringModelAssignatures = [];
    //         $model = $this->createModel($rowData);
    //         if (method_exists($model, 'getStringModelAssignatures')) {
    //             $stringModelAssignatures = $model->getStringModelAssignatures();
    //         }
    //         $modelArray = $model->toArray();
    //         // $arrangedRowData = self::arrangeInputValues($modelArray, $this->dataLabels, $rawTableData[$tableRowCounter]);
    //         $arrangedRowData = self::arrangeInputValues($modelArray, $this->dataLabels, $rawTableBodyDataRow);
    //         $rules = $this->getModelRules($model, $this->validationAdditionalData);
    //         $validator = Validator::make($modelArray, $rules);
    //         $models['row-'.$tableRowCounter] = $model;

    //         $validationErrors = [];
    //         if ($validator->fails()) {
    //             $validationErrors = $validator->errors()->toArray();
    //             foreach ($validationErrors as $property => $validationErrorArray) {
    //                 if (in_array($property, $visibleProperties)) {
    //                     $totalValidationErrorsCount += count($validationErrorArray);
    //                 }
    //             }
    //         }
    //     }

    //     return $this;
    // }

    abstract public function createModel(array $tableBodyRowData): object;

    public function getModelRules()
    {
        return [];
    }

    /**
     * Some additional info:
     * - You can force a property to be required, regardless of the rules of that property. Just send it to the $forceRequired argument.
     * - A property only will trigger a "missingRequiredColumn" error, if
     *   > it exists in the Rules, and also exists in the $this->formData, OR:
     *   > it exists in the $forceRequired argument.
     */
    public function detectMissingRequiredColumns(array $forceRequired = [])
    {
        $missingRequiredColumns = [];
        $propertiesFoundInFile = [];
        $formRules = $this->getModelRules();

        /**
         * Iterating formData.
         */
        foreach ($this->formData as $formDataRow) {
            /**
             * Looped property of the LW-component's formData
             */
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];

            $translationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
            $translatedLabel = Lang::get($translationReference, [], $this->data['language']);
            $propertyFoundInFile = false;

            /**
             * Checking if the looped property exists and identified in the "processedLabels".
             */
            foreach ($this->data['processedLabels'] as $processedLabel) {
                $identifiedProperty = $processedLabel['identifiedProperty'];
                if ($identifiedProperty && $identifiedProperty === $property) {
                    $propertyFoundInFile = true;
                    $propertiesFoundInFile[] = $property;
                    break;
                }
            }

            $isRequired = in_array($property, $forceRequired) || (isset($formRules[$property]) && in_array('required', $formRules[$property]));

            /**
             * Searching required_without_all or required_with rule, point them missing according to $rawDataLabels.
             */
            if (isset($formRules[$property])) {
                foreach ($formRules[$property] as $formRule) {
                    if (is_string($formRule) && strpos($formRule, 'required_without_all:') === 0) {
                        $requiredWithoutAllProperties = explode(',', str_replace('required_without_all:', '', $formRule));
                        $allMissing = true;
                        foreach ($requiredWithoutAllProperties as $requiredWithoutProperty) {
                            if (in_array($requiredWithoutProperty, $propertiesFoundInFile)) {
                                $allMissing = false;
                                break;
                            }
                        }
                        if ($allMissing) {
                            $isRequired = true;
                        }
                    }

                    if (is_string($formRule) && strpos($formRule, 'required_with:') === 0) {
                        $requiredWithProperties = explode(',', str_replace('required_with:', '', $formRule));
                        $anyPresent = false;
                        foreach ($requiredWithProperties as $requiredWithProperty) {
                            if (in_array($requiredWithProperty, $propertiesFoundInFile)) {
                                $anyPresent = true;
                                break;
                            }
                        }
                        if ($anyPresent) {
                            $isRequired = true;
                        }
                    }
                }
            }

            if (! $propertyFoundInFile && $isRequired) {
                $missingRequiredColumns[] = $translatedLabel;
            }

            // if ($property == '')
        }

        return $missingRequiredColumns;
    }

    public function render()
    {
        return null;
    }
}
