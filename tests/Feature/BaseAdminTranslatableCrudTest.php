<?php

namespace Tests\Feature;

use Domain\Language\Models\Language;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Illuminate\Support\Collection;
use Livewire\Livewire;

abstract class BaseAdminTranslatableCrudTest extends BaseAdminCrudTest
{
    public array $translatableFields = [];

    private array $systemLanguages = [
        ['name' => 'Magyar', 'locale' => 'hu', 'is_default' => 1],
        ['name' => 'Angol', 'locale' => 'en', 'is_default' => 0],
    ];

    private Collection $locales;

    protected function setUp(): void
    {
        parent::setUp();
        Language::insert($this->systemLanguages);
        $this->locales = collect($this->systemLanguages)->pluck('locale');
    }

    public function test_livewireForm_create_translatable(): void
    {
        Livewire::test($this->editFormClass, [
            'model' => new $this->modelClass,
            'actionType' => BaseEditComponent::ACTION_TYPE_NEW,
        ])
            ->set($this->buildFormFieldsWithAllTranslations($this->createTestFields))
            ->call('save');

        $createdModel = $this->modelClass::query()->orderBy('id', 'desc')->first();
        expect($createdModel)->toBeInstanceOf($this->modelClass);
        $this->expectModelHasAllFieldsTranslatedToAllLocales($createdModel, $this->createTestFields);
    }

    public function test_livewireForm_edit_translatable(): void
    {

        Livewire::test($this->editFormClass, [
            'model' => $this->sampleModel,
            'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        ])
            ->set($this->buildFormFieldsWithAllTranslations($this->editTestFields))
            ->call('save');

        $this->sampleModel->refresh();

        $this->expectModelHasAllFieldsTranslatedToAllLocales($this->sampleModel, $this->editTestFields);
    }

    private function buildFormFieldsWithAllTranslations($baseFields): array
    {
        $formFields = $this->getFormFields($this->createTestFields);

        foreach ($this->locales as $locale) {
            foreach ($this->translatableFields as $translatableField) {
                $formFields['form.translations.'.$locale.'.'.$translatableField] = sprintf('%s - %s', $locale, $formFields['form.'.$translatableField]);
            }
        }

        return $formFields;
    }

    private function expectModelHasAllFieldsTranslatedToAllLocales($model, $baseFields): void
    {
        $this->locales->each(fn ($locale) => collect($this->translatableFields)
            ->each(fn ($field) => expect($model->getTranslation($field, $locale))
                ->toEqual(sprintf('%s - %s', $locale, $this->buildFormFieldsWithAllTranslations($baseFields)['form.'.$field])))
        );
    }
}
