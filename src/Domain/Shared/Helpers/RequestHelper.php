<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\Route;

class RequestHelper
{
    public static function getCurrentRequest()
    {
        return Route::getCurrentRequest();
    }
}
