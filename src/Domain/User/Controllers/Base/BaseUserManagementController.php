<?php

namespace Domain\User\Controllers\Base;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class BaseUserManagementController extends BaseContentController
{
    use AuthorizesRequests, ValidatesRequests;
}
