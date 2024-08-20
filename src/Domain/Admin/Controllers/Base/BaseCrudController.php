<?php

namespace Domain\Admin\Controllers\Base;

use Domain\Shared\Livewire\Base\BaseEditComponent;
use Illuminate\Http\Request;

class BaseCrudController extends BaseAdminController
{
    public string $modelClass = '';

    public string $domain = '';

    public string $titleLangKey = '';

    public string $viewDirectory = 'admin';

    public array $defaultCreateModelParams = [];

    public function list(Request $request)
    {
        return $this->renderContent($request, $this->domain.'.'.$this->viewDirectory.'.list', __($this->titleLangKey));
    }

    public function show(?string $id = null)
    {
        $model = $this->modelClass::findOrFail($id);

        return $this->renderContent(request(), $this->domain.'.'.$this->viewDirectory.'.show', __($this->titleLangKey), [
            'model' => $model,
        ]);
    }

    public function edit(?string $id = null)
    {
        $model = $this->modelClass::find($id);
        if (! $model) {
            $model = new $this->modelClass($this->defaultCreateModelParams);
        }

        return $this->renderContent(request(), $this->domain.'.'.$this->viewDirectory.'.edit', __($this->titleLangKey), [
            'menu' => $this->getMenuData(),
            'model' => $model,
            'actionType' => $model->id ? BaseEditComponent::ACTION_TYPE_EDIT : BaseEditComponent::ACTION_TYPE_NEW,
        ]);
    }
}
