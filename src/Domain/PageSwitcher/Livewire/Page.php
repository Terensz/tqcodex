<?php

namespace Domain\PageSwitcher\Livewire;

use Domain\Shared\Livewire\Base\BaseLivewireComponent;

class Page extends BaseLivewireComponent
{
    public $actionType;

    public static function getComponentName(): string
    {
        return 'page';
    }

    public function render()
    {
        $viewParams = [
        ];

        return view('public.page-switcher.page-livewire', $viewParams);
    }
}
