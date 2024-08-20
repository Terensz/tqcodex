<?php

namespace Domain\Shared\Livewire\Base;

use Domain\Shared\Events\ModelDeleteRequested;
use Illuminate\Database\QueryException;
use ReflectionClass;

abstract class BaseEditComponent extends BaseLivewireComponent
{
    public const ACTION_TYPE_NEW = 'new';

    public const ACTION_TYPE_EDIT = 'edit';

    public const ACTION_TYPE_VIEW = 'view';

    public $actionType;

    public $dropdownParams = [];

    public $model;

    /**
     * Megjegyzés: itt az alapértelmezett mount() létezhetne, a gyerek osztályokban legfeljebb felül kell írni, ha szükséges.
     */

    /*
    public function mount($model, string $actionType)
    {
        if (isset($this->form)) {
            $this->form->setModel($model);
            $this->form->setActionType($actionType);
        }
        $this->actionType = $actionType;
    }
    */

    public static function getComponentName(): string
    {
        return sprintf('%s-edit', self::getEntity());
    }

    protected static function getEntity()
    {
        $reflection = new ReflectionClass(static::class);
        $className = $reflection->getShortName(); // Get the class name without namespace

        // Extract the part before "Edit" and convert it to lowercase
        $tagName = strtolower(substr($className, 0, -4)); // Assuming "Edit" is always at the end

        return $tagName;
    }

    public function getEditRouteComponents(): array
    {
        return [
            'name' => sprintf('admin.%s.edit', self::getEntity()),
            'paramArray' => [
                'model' => $this->getEntityObject(),
            ],
        ];
    }

    public function getEntityObject()
    {
        return isset($this->form) ? $this->form->model : null;
    }

    public function getEditRoute()
    {
        $components = $this->getEditRouteComponents();

        return route($components['name'], $components['paramArray']);
    }

    public function getListRoute()
    {
        return sprintf('admin.%s.list', self::getEntity());
    }

    public function getForm()
    {
        return $this->form ?? null;
    }

    public function render()
    {
        $viewParams = [
            'formData' => isset($this->form) ? $this->form::getFormData($this->model, $this->actionType) : null,
            'pageTitle' => (isset($this->form) && $this->form->model->id)
                ? __('shared.EditItem', ['item' => __(self::getEntity().'.Singular')])
                : __('shared.CreateNewItem', ['item' => __(self::getEntity().'.Singular')]),
            'pageShortDescription' => __(self::getEntity().'.EditLanguageDescription'),

            'translateFields' => isset($this->form) ? $this->form->model->translatable : null,
        ];

        return view('common.general-edit-form', $viewParams);
    }

    public function save()
    {
        try {
            if ($this->getForm()) {
                $this->getForm()->store();
            }

            $dispatchData = [
                'editRoute' => $this->getEditRoute(),
                'actionType' => $this->actionType,
                'id' => $this->getEntityObject()->id,
            ];

            if (is_numeric($dispatchData['id']) && $dispatchData['id'] > 0 && $dispatchData['actionType'] === BaseEditComponent::ACTION_TYPE_NEW) {
                $routeComponents = $this->getEditRouteComponents();

                return redirect()->route($routeComponents['name'], $routeComponents['paramArray'])->with('success', __('shared.SaveSuccessful'));
            }

            $this->dispatch('save-successful', $dispatchData);

            /**
             * This must not be \Exception, because than the validation system collapses.
             */
        } catch (QueryException $e) {
            $this->dispatch('save-failed');
        }
    }

    public function loadPropertiesToEntityObject()
    {
        if (isset($this->form) && $this->form) {
            $formData = $this->form::getFormData();
            foreach ($formData as $formDataRow) {
                $property = $formDataRow['property'];
                $this->getEntityObject()->$property = $this->$property;
            }
        }
    }

    public function entityObjectIsDeletable()
    {
        return method_exists($this->getEntityObject(), 'isDeletable') ? $this->getEntityObject()->isDeletable() : true;
    }

    public function delete()
    {
        if (! $this->entityObjectIsDeletable()) {
            return;
        }

        ModelDeleteRequested::dispatch($this->getEntityObject());

        return redirect()
            ->route($this->getListRoute())
            ->with('success', __('shared.DeleteSuccessful'));
    }
}
