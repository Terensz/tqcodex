<?php

namespace App\Http\Middleware;

use Closure;
use Domain\Admin\Models\Admin;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectAdminIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_ADMIN);
        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        $isAdmin = false;
        if ($user && $user instanceof Admin && $guardObject->check()) {
            $isAdmin = true;
        }

        if ($isAdmin) {
            return Redirect::to(UserService::getLogoutRoute(UserService::ROLE_TYPE_ADMIN));
        }

        return $next($request);
    }
}
