<?php

namespace Domain\ElastiSite\Livewire;

use Domain\Admin\Livewire\Forms\PermissionEditForm;
use Domain\Shared\Livewire\Base\BaseLivewireComponent;
use Domain\User\Models\Permission;
use Domain\User\Services\UserService;

class Page extends BaseLivewireComponent
{
    public $actionType;

    public static function getComponentName(): string
    {
        return 'page';
    }
}
