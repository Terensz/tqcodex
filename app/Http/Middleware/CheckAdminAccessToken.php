<?php

namespace App\Http\Middleware;

use Closure;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;

class CheckAdminAccessToken
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
        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);
        $accessToken = AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN);

        return $next($request);
    }
}
