<?php

namespace App\Http\Middleware;

use Closure;
use Domain\User\Services\UserService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class RegisterSessionData extends Middleware
{
    public function handle($request, Closure $next, ...$permissions)
    {
        UserService::registerSessionData();

        return $next($request);
    }
}
