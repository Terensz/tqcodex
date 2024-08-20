<?php

namespace Tests\Feature;

use Domain\Shared\Livewire\Base\BaseEditComponent;
use Livewire\Livewire;

trait TestsSlug
{
    public function test_livewireForm_create_slug_is_generated(): void
    {
        $formFields = $this->getFormFields($this->createTestFields);

        Livewire::test($this->editFormClass, [
            'model' => new $this->modelClass,
            'actionType' => BaseEditComponent::ACTION_TYPE_NEW,
        ])
            ->set($formFields)
            ->call('save');

        $createdModel = $this->modelClass::query()->orderBy('id', 'desc')->first();
        expect($createdModel->slug)->toEqual(\Str::slug($this->createTestFields[$this->slugSourceField]));
    }
}
