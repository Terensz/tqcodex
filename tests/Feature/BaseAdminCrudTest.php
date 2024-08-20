<?php

namespace Tests\Feature;

use Domain\Admin\Models\User;
use Domain\Language\Livewire\LanguageEdit;
use Domain\Language\Models\Language;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

abstract class BaseAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    public $admin;

    public string $entity = 'language';

    public string $modelClass = Language::class;

    public string $editFormClass = LanguageEdit::class;

    public Model $sampleModel;

    public array $sampleModelValues = [];

    public array $createTestFields = [];

    public array $editTestFields = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        UserRoleService::assignRoleToAdmin(RoleService::ROLE_SUPER_ADMIN, $this->admin);

        $this->actingAs($this->admin, UserService::GUARD_ADMIN);

        $this->sampleModel = $this->modelClass::create($this->sampleModelValues);
    }

    public function test_httpList_page(): void
    {
        $response = $this->get(route('admin.'.$this->entity.'.list'));
        $response->assertOk();
        $response->assertSeeLivewire($this->entity.'-list');
        $response->assertSee(array_values($this->sampleModelValues)[0]);
    }

    public function test_httpCreate_page(): void
    {
        $response = $this->get(route('admin.'.$this->entity.'.edit'));
        $response->assertOk();
        $response->assertSeeLivewire($this->entity.'-edit');
        $response->assertDontSee(array_values($this->sampleModelValues)[0]);
    }

    public function test_httpEdit_page(): void
    {
        $response = $this->get(route('admin.'.$this->entity.'.edit', ['model' => $this->sampleModel]));
        $response->assertOk();
        $response->assertSeeLivewire($this->entity.'-edit');
        $response->assertSee(array_values($this->sampleModelValues)[0]);
    }

    public function test_livewireForm_create(): void
    {
        $formFields = $this->getFormFields($this->createTestFields);

        Livewire::test($this->editFormClass, [
            'model' => new $this->modelClass,
            'actionType' => BaseEditComponent::ACTION_TYPE_NEW,
        ])
            ->set($formFields)
            ->call('save');

        $createdModel = $this->modelClass::query()->orderBy('id', 'desc')->first();
        expect($createdModel)->toBeInstanceOf($this->modelClass);
        collect($this->createTestFields)->each(fn ($value, $field) => expect($createdModel->{$field})->toEqual($value));
    }

    public function test_livewireForm_edit(): void
    {
        $formFields = $this->getFormFields($this->editTestFields);

        Livewire::test($this->editFormClass, [
            'model' => $this->sampleModel,
            'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        ])
            ->set($formFields)
            ->call('save');

        $this->sampleModel->refresh();

        collect($this->editTestFields)->each(fn ($value, $field) => expect($this->sampleModel->{$field})->toEqual($value));
    }

    public function test_livewireForm_delete(): void
    {
        Livewire::test($this->editFormClass, [
            'model' => $this->sampleModel,
            'actionType' => BaseEditComponent::ACTION_TYPE_EDIT,
        ])
            ->call('delete');

        $deleted = $this->modelClass::find($this->sampleModel->id);
        expect($deleted)->toBeNull();
    }

    protected function getFormFields(array $formFields): array
    {
        return collect($formFields)->map(fn ($value, $key) => ["form.$key" => $value])->collapse()->toArray();
    }
}
