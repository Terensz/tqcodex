<?php

namespace App\Http\Middleware;

use Closure;
use Domain\User\Services\UserService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateAdmin extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_ADMIN);
        if ($guardObject && $guardObject->check()) {
            return $next($request);
        } else {
            return redirect(UserService::getLoginRoute(UserService::ROLE_TYPE_ADMIN));
        }
    }
}
