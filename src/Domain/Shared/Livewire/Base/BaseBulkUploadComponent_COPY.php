<?php

namespace Domain\Shared\Livewire\Base;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

abstract class BaseBulkUploadComponent_COPY extends BaseLivewireComponent
{
    use WithFileUploads;

    #[Validate('file|max:1024')] // 1MB Max
    public $file;

    public $fileContentData;

    public $language;

    public $dataLabels = [];

    public $formData = [];

    public $models = [];

    /**
     * It's usually an e-mail address or a phone number.
     * The purpose is: to save some memory when assembling this array while processing
     * the posted data in the method calculateData().
     */
    public $collectUniqueProperties = [];

    /**
     * This array will be filled, if the array above is also filled.
     * This variable contains the unique values.
     */
    public $collectedUniqueProperties = [];

    public $unusedFormData = [];

    public $unidentifiedDataLabels = [];

    public $arrangedFormalErrors = [];

    public $errorStats = [
        'totalErrorsCount' => 0,
        'criticalErrorsCount' => 0,
        'nonCriticalErrorsCount' => 0,
        'totalValidationErrorsCount' => 0,
    ];

    public $tableBodyData = [];

    public static function arrangeInputValues(array $modelArray, array $tableDataLabels, array $rawInputDataRow): array
    {
        $arrangedInputValues = [];

        $index = 0;
        foreach ($tableDataLabels as $tableDataLabel) {
            $fileDataProperty = $tableDataLabel['identifiedProperty'];
            if ($fileDataProperty) {
                // if (! isset($modelArray[$fileDataProperty])) {
                //     // dump($modelArray); exit;
                // }
                $arrangedInputValues[] = $modelArray[$fileDataProperty];
            } else {
                $arrangedInputValues[] = $rawInputDataRow[$index];
            }

            $index++;
        }

        return $arrangedInputValues;
    }

    public static function associateCellValues(array $tableBodyDataRow, array $dataLabels): array
    {
        $result = [];
        foreach ($tableBodyDataRow as $index => $cellValue) {
            if (isset($dataLabels[$index])) {
                $identifiedProperty = $dataLabels[$index]['identifiedProperty'];
                if (! empty($identifiedProperty)) {
                    $result[$identifiedProperty] = $cellValue;
                }
                // $result[$identifiedProperty] = $cellValue;
            }
        }

        // dump($result);

        return $result;
    }

    /**
     * Detecting the data sequence of the csv file.
     * We also detect the unidentifiable labels.
     */
    public static function detectMissingRequiredColumns(array $formData, array $rawDataLabels, array $formRules, string $language): array
    {
        $missingRequiredColumns = [];
        $propertiesFoundInFile = [];

        foreach ($formData as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            $translationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
            $translatedLabel = Lang::get($translationReference, [], $language);
            $propertyFoundInFile = false;

            foreach ($rawDataLabels as $dataLabel) {
                $fileDataProperty = $dataLabel['identifiedProperty'];
                if ($fileDataProperty && $fileDataProperty === $property) {
                    $propertyFoundInFile = true;
                    $propertiesFoundInFile[] = $property;
                    break;
                }
            }

            $isRequired = isset($formRules[$property]) && in_array('required', $formRules[$property]);

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
        }

        return $missingRequiredColumns;
    }

    public static function getPropertyTranslation($formData, $searchedProperty, $language)
    {
        foreach ($formData as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            if ($property === $searchedProperty) {
                $translationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
                $translatedLabel = Lang::get($translationReference, [], $language);

                return $translatedLabel;
            }
        }
    }

    /**
     * Detecting the data sequence of the csv file.
     * We also detect the unidentifiable labels.
     */
    public static function getDataLabels(array $formData, array $rawDataLabels, string $language, array $automaticLabelTransformations = []): array
    {
        $dataLabels = [];
        foreach ($rawDataLabels as $columnIndex => $originalLabel) {
            $labelFound = false;
            foreach ($formData as $formDataRow) {
                $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
                $translationReference = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE];
                $translatedLabel = Lang::get($translationReference, [], $language);
                foreach ($automaticLabelTransformations as $automaticLabelTransformationFromRef => $automaticLabelTransformationToRef) {
                    $automaticLabelTransformationFromLabel = Lang::get($automaticLabelTransformationFromRef, [], $language);
                    if ($translatedLabel === $automaticLabelTransformationFromLabel) {
                        $translatedLabel = Lang::get($automaticLabelTransformationToRef, [], $language);
                    }
                }
                // dump($translatedLabel . ' - ' . $originalLabel);
                if ($translatedLabel === $originalLabel) {
                    $dataLabels[$columnIndex] = [
                        'identifiedProperty' => $property,
                        'identifiedTranslatedLabel' => $translatedLabel,
                    ];
                    $labelFound = true;
                }
            }

            if (! $labelFound) {
                $dataLabels[$columnIndex] = [
                    'identifiedProperty' => null,
                    'identifiedTranslatedLabel' => null,
                ];
            }

            $dataLabels[$columnIndex]['originalLabel'] = $originalLabel;
        }

        return $dataLabels;
    }

    public static function collectDataLabelIdentifiedProperties($dataLabels): array
    {
        $identifoedProperties = [];
        foreach ($dataLabels as $dataLabel) {
            $identifoedProperties[] = $dataLabel['identifiedProperty'];
        }

        return $identifoedProperties;
    }

    public static function extractOfferableFormData($formData): array
    {
        $offerableFormData = [];
        // $dataLabelIdentifiedProperties = self::collectDataLabelIdentifiedProperties($dataLabels);
        foreach ($formData as $formDataRow) {
            if (! array_key_exists(BaseLivewireForm::OFFERABLE_AS_BULK_LABEL, $formDataRow)) {
                $formDataRow[BaseLivewireForm::OFFERABLE_AS_BULK_LABEL] = BaseLivewireForm::DEFAULT_OFFERABLE_AS_BULK_LABEL;
            }

            if ($formDataRow[BaseLivewireForm::OFFERABLE_AS_BULK_LABEL]) {
                $offerableFormData[] = $formDataRow;
            }
        }

        return $offerableFormData;
    }

    public static function getUnusedFormData($formData, $dataLabels): array
    {
        $unusedFormData = [];
        $dataLabelIdentifiedProperties = self::collectDataLabelIdentifiedProperties($dataLabels);
        foreach ($formData as $formDataRow) {
            // dump($formDataRow);
            if (! isset($formDataRow[BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY])) {
                $formDataRow[BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY] = false;
            }
            $cannotBeOfferedAsUnusedProperty = $formDataRow[BaseLivewireForm::CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY];
            $propertyExistsAndNotLabelYet = isset($formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY]) && ! in_array($formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY], $dataLabelIdentifiedProperties);
            // $propertyHasInputType = isset($formDataRow[BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE]);
            // if ($propertyExistsAndNotLabelYet && $propertyHasInputType && ! $cannotBeOfferedAsUnusedProperty) {
            if ($propertyExistsAndNotLabelYet && ! $cannotBeOfferedAsUnusedProperty) {
                $unusedFormData[] = $formDataRow;
            }
        }

        return $unusedFormData;
    }

    /**
     * Detecting the language of the csv file.
     */
    public static function findLanguage(array $formData, array $dataLabels, \Illuminate\Support\Collection|array $languages): ?string
    {
        $foundLanguages = [];
        foreach ($formData as $formDataRow) {
            foreach ($dataLabels as $label) {
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
            return $foundLanguages[0];
        }

        if (count($foundLanguages) === 0) {
            return null;
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

        // dump($mostHitsLanguage);

        return $mostHitsLanguage;
    }

    /**
     * Very simple. We count all the language directories.
     */
    public static function getLanguages(): \Illuminate\Support\Collection
    {
        $languages = collect(File::directories(base_path('lang')))->map(function ($path) {
            return basename($path);
        });

        return $languages;
    }

    /*
        ##################################
        ##                              ##
        ## Processing the uploaded file ##
        ##                              ##
        ##################################
    */

    /**
     * Sorting the file content into an array.
     */
    public static function getFileContentData(\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file): array
    {
        // dump($file);exit;
        $path = $file->getRealPath();
        $contents = file_get_contents($path);
        $rows = explode("\n", $contents);
        $dataLabels = [];
        $tableBodyData = [];
        $rowIndex = 0;
        $noLabelRow = true;
        /**
         * Resetting the count of the total columns of each row.
         * This data must be determined by the total coulmns number of the label row.
         */
        // $labelColsCount = 0;
        $maxColumnCount = 0;
        $maxColumnCount_rows = 0;
        $columnCount_label = 0;
        $columnCount_rows = [];

        /**
         * We must collect all row indexes, in which the number of columns are differing from the number of the labels.
         */
        // $differingColumnCountRowIndexes = [];
        $valueRowsHavingExtraColumns = [];
        $valueRowsHavingLessColumns = [];
        foreach ($rows as $row) {
            $rowData = str_getcsv($row, ';');

            if (count($rowData) > $maxColumnCount) {
                $maxColumnCount = count($rowData);
            }

            /**
             * Label row
             */
            if ($rowIndex === 0) {
                if (count($rowData) > $maxColumnCount_rows) {
                    $maxColumnCount_rows = count($rowData);
                }
                $noLabelRow = false;
                $dataLabels = $rowData;
                /**
                 * We determine this at the label row.
                 */
                $columnCount_label = count($rowData);
            }
            /**
             * Value row
             */
            elseif (! (count($rowData) === 1 && empty($rowData[0]))) {
                $tableBodyData[] = $rowData;
                if (! isset($columnCount_rows[count($rowData)])) {
                    $columnCount_rows[count($rowData)] = [];
                }
                $columnCount_rows[count($rowData)][] = $rowIndex;

                if (count($rowData) > $columnCount_label) {
                    // $differingColumnCountRowIndexes[] = $rowIndex;
                    $valueRowsHavingExtraColumns[] = $rowIndex;
                }

                if (count($rowData) < $columnCount_label) {
                    $valueRowsHavingLessColumns[] = $rowIndex;
                }
            }

            $rowIndex++;
        }

        /**
         * Error: labelRowHavingExtraColumns
         */
        $labelRowExtraColumnCount = 0;
        if ($columnCount_label > $maxColumnCount_rows) {
            $labelRowExtraColumnCount = $columnCount_label - $maxColumnCount_rows;
        }

        $mismatchingColumnCountRows = [];
        if (count($columnCount_rows) > 1) {
            foreach ($columnCount_rows as $count => $rowIndexes) {
                if ($count !== $columnCount_label) {
                    foreach ($rowIndexes as $rowIndex) {
                        $mismatchingColumnCountRows[$rowIndex] = $count;
                    }
                }
            }
        }

        $return = [
            'maxColumnCount' => $maxColumnCount,
            'columnCount_label' => $columnCount_label,
            'columnCount_rows' => $columnCount_rows,
            'rawDataLabels' => $dataLabels,
            'rawTableBodyData' => $tableBodyData,
            'errors' => [
                'totalColumnCount' => $maxColumnCount,
                'labelRowExtraColumnCount' => $labelRowExtraColumnCount,
                'valueRowsHavingExtraColumns' => $valueRowsHavingExtraColumns,
                'valueRowsHavingLessColumns' => $valueRowsHavingLessColumns,
                'mismatchingColumnCountRows' => $mismatchingColumnCountRows,
                // 'bodyColumnCount' => $bodyColumnCount,
                'noLabelRow' => $noLabelRow,
                // 'differingColumnCountRowIndexes' => $differingColumnCountRowIndexes,
            ],
        ];

        // dump($return);
        // exit;

        return $return;
    }

    /*
        ############################
        ##                        ##
        ## Handling formal errors ##
        ##                        ##
        ############################
    */

    /**
     * We don't want to carry the non-triggered errors to the view.
     */
    public static function dissmissNonTriggeredErrors($errors)
    {
        $updatedErrors = [];
        foreach ($errors as $key => $error) {
            if ($error['type'] === 'boolean' && $error['value']) {
                $updatedErrors[$key] = $error;
            }
            if ($error['type'] === 'collection' && ! empty($error['valueArray'])) {
                $updatedErrors[$key] = $error;
            }
        }

        return $updatedErrors;
    }

    /**
     * Counting all the errors of the uploaded file. This does not contain the validation errors.
     */
    public static function arrangeFormalErrors($formalErrors)
    {
        $totalErrorsCount = 0;
        $criticalErrorsCount = 0;
        $criticalErrors = [];
        $nonCriticalErrorsCount = 0;
        $nonCriticalErrors = [];
        foreach ($formalErrors as $key => $params) {
            if ($params['type'] === 'boolean') {
                if ($params['value']) {
                    $totalErrorsCount++;
                    if ($params['critical']) {
                        $criticalErrorsCount += 1;
                        $criticalErrors[$key] = $params;
                    } else {
                        $nonCriticalErrorsCount += 1;
                        $nonCriticalErrors[$key] = $params;
                    }
                }
            }

            if ($params['type'] === 'collection') {
                if (! empty($params['valueArray'])) {
                    foreach ($params['valueArray'] as $valueArrayRow) {
                        $totalErrorsCount++;
                    }
                    if ($params['critical']) {
                        $criticalErrorsCount += count($params['valueArray']);
                        $criticalErrors[$key] = $params;
                    } else {
                        $nonCriticalErrorsCount += count($params['valueArray']);
                        $nonCriticalErrors[$key] = $params;
                    }
                }
            }
        }

        $return = [
            'totalErrorsCount' => $totalErrorsCount,
            'criticalErrorsCount' => $criticalErrorsCount,
            'criticalErrors' => $criticalErrors,
            'nonCriticalErrorsCount' => $nonCriticalErrorsCount,
            'nonCriticalErrors' => $nonCriticalErrors,
        ];

        // dump($return); exit;

        return $return;
    }
}
