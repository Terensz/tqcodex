<?php

namespace App\Http\Middleware;

use Closure;
use Domain\Shared\Helpers\PHPHelper;
use Domain\User\Services\UserService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Redirect;

class RedirectUnrecognizedRoute extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $pathInfoParts = PHPHelper::explode('/', PHPHelper::trim($request->getPathInfo(), '/'));
        if (isset($pathInfoParts[0]) && $pathInfoParts[0] == UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_ADMIN]) {
            return Redirect::to(UserService::getLogoutRoute(UserService::ROLE_TYPE_ADMIN));
        }
        if (isset($pathInfoParts[0]) && $pathInfoParts[0] == UserService::ROUTE_PREFIXES[UserService::ROLE_TYPE_CUSTOMER]) {
            return Redirect::to(UserService::getLogoutRoute(UserService::ROLE_TYPE_CUSTOMER));
        }

        return abort(404);
    }
}
