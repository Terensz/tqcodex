<?php

namespace Domain\Shared\Livewire\Base;

use Domain\Language\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Form;

abstract class BaseLivewireForm extends Form
{
    public const FORM_SECTION = 'formSection';

    public const FORM_SECTION_ITEMS = 'formSectionItems';

    public const CLASSES = 'classes';

    public const DATA_TYPE = 'dataType';

    public const DATA_TYPE_TECHNICAL = 'technical';

    public const FORM_DATA_KEY_PROPERTY = 'property';

    public const FORM_DATA_KEY_INPUT_TYPE = 'inputType';

    public const FORM_DATA_KEY_TRANSLATION_REFERENCE = 'translationReference';

    public const FORM_DATA_KEY_DEFER = 'defer';

    public const FORM_DATA_KEY_OPTIONS = 'options';

    public const OFFERABLE_AS_BULK_LABEL = 'offerableAsBulkLabel';

    public const DEFAULT_OFFERABLE_AS_BULK_LABEL = true;

    public const CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY = 'CANNOT_BE_OFFERED_AS_UNUSED_PROPERTY';

    public ?Model $model;

    public string $actionType;

    public ?array $translations;

    public static bool $renderTranslationFields = true;

    abstract public function getModelRules();

    abstract public static function getFormData();

    public function setActionType(string $actionType)
    {
        $this->actionType = $actionType;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;

        static::getFormFields()->each(function ($property) use ($model) {
            if (! is_null($model->{$property})) {
                $this->{$property} = $model->{$property};
            }

            if (is_a($this->{$property}, Collection::class)) {
                $this->{$property} = $this->{$property}->map(fn ($item) => $item->id)->toArray();
            }
        });

        if (! static::$renderTranslationFields) {
            return;
        }
        if (! isset($this->model->translatable)) {
            return;
        }

        $secondaryLanguages = Language::getSystemLanguages()->where('is_default', 0);
        foreach ($secondaryLanguages as $language) {
            foreach ($this->model->translatable as $translatableField) {
                if (! method_exists($this->model, 'getTranslation')) {
                    continue;
                }
                $this->translations[$language->locale][$translatableField] = $this->model->getTranslation($translatableField, $language->locale);
            }
        }
    }

    public function store()
    {
        $this->validate($this->getModelRules());

        static::getFormFields()->each(function ($property) {
            if (is_array($this->{$property})) {
                return;
            } // saving relationships later
            $this->model->{$property} = $this->{$property};
        });

        foreach ($this->translations as $locale => $fields) {
            foreach ($fields as $fieldName => $fieldValue) {
                if (! method_exists($this->model, 'setTranslation')) {
                    continue;
                }
                $this->model->setTranslation($fieldName, $locale, $fieldValue);
            }
        }

        $this->model->save();

        // saving relationships
        static::getFormFields()->each(function ($property) {
            if (! is_array($this->{$property})) {
                return;
            } // saving relationships later
            $this->model->{$property}()->sync($this->{$property});
        });
    }

    public static function getFormFields(): Collection
    {
        if (! method_exists(static::class, 'getFormData')) {
            return collect([]);
        }

        return collect(static::getFormData())->flatMap(function ($item) {
            if (isset($item['formSectionItems'])) {
                return collect($item['formSectionItems'])->map(function ($nestedItem) {
                    return $nestedItem['property'] ?? null;
                });
            } else {
                return [$item['property'] ?? null];
            }
        });
    }

    public function isEditAction()
    {
        return $this->actionType === BaseEditComponent::ACTION_TYPE_EDIT;
    }

    public function isCreateAction()
    {
        return $this->actionType === BaseEditComponent::ACTION_TYPE_NEW;
    }
}
