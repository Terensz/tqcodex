<?php

namespace Tests\Feature;

use Domain\Shared\Livewire\Base\BaseEditComponent;
use Livewire\Livewire;

trait TestsRelationships
{
    public function test_livewireForm_create_relationships(): void
    {
        $formFields = $this->getFormFields($this->createTestFields);
        collect($this->relationShips)->each(function ($models, $field) use (&$formFields) {
            $formFields['form.'.$field] = $models->pluck('id')->toArray();
        });

        Livewire::test($this->editFormClass, [
            'model' => new $this->modelClass,
            'actionType' => BaseEditComponent::ACTION_TYPE_NEW,
        ])
            ->set($formFields)
            ->call('save');

        $createdModel = $this->modelClass::query()->orderBy('id', 'desc')->first();
        expect($createdModel)->toBeInstanceOf($this->modelClass);

        collect($this->relationShips)->each(fn ($val, $relationship) => expect($createdModel->{$relationship}->pluck('id'))->toEqual($this->relationShips[$relationship]->pluck('id'))
        );
    }

    public function test_livewireForm_edit_relationships(): void
    {
        $formFields = $this->getFormFields($this->editTestFields);
        collect($this->relationShips)->each(function ($models, $field) use (&$formFields) {
            $formFields['form.'.$field] = $models->pluck('id')->toArray();
        });

        Livewire::test($this->editFormClass, [
            'model' => $this->sampleModel,
            'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        ])
            ->set($formFields)
            ->call('save');

        $this->sampleModel->refresh();

        collect($this->relationShips)->each(fn ($val, $relationship) => expect($this->sampleModel->{$relationship}->pluck('id'))->toEqual($this->relationShips[$relationship]->pluck('id'))
        );
    }
}
