<?php

namespace Domain\Language\Livewire\Forms;

use Domain\Language\Models\Language;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseLivewireForm;
use Domain\Shared\Livewire\EditFormInterface;
use Illuminate\Database\Eloquent\Model;

class LanguageEditForm extends BaseLivewireForm implements EditFormInterface
{
    public ?Model $model;

    public $name = '';

    public $locale = '';

    public $is_default = '0';

    public function getModelRules()
    {
        return [
            'name' => [
                'required',
            ],
            'locale' => [
                'required',
            ],
        ];
    }

    public static function getFormData(?Model $model = null, ?string $actionType = null): array
    {
        $isDefaultFieldOpts = [
            [
                ValidationHelper::OPTION_VALUE => 1,
                ValidationHelper::OPTION_LABEL => __('shared.bool.Yes'),
            ],
            [
                ValidationHelper::OPTION_VALUE => 0,
                ValidationHelper::OPTION_LABEL => __('shared.bool.No'),
            ],
        ];

        return [
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'name',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'language.Name',
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'locale',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'language.Locale',
            ],
            [
                BaseLivewireForm::FORM_DATA_KEY_PROPERTY => 'is_default',
                BaseLivewireForm::FORM_DATA_KEY_INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                BaseLivewireForm::FORM_DATA_KEY_TRANSLATION_REFERENCE => 'language.Default',
                BaseLivewireForm::FORM_DATA_KEY_OPTIONS => $isDefaultFieldOpts,
            ],
        ];
    }

    public function store()
    {
        $this->validate($this->getModelRules());

        if ($this->is_default === 1) {
            Language::where('is_default', 1)->update(['is_default' => 0]);
        }

        foreach (self::getFormData() as $formDataRow) {
            $property = $formDataRow[BaseLivewireForm::FORM_DATA_KEY_PROPERTY];
            $this->model->{$property} = $this->{$property};
        }

        $this->model->save();
    }
}
