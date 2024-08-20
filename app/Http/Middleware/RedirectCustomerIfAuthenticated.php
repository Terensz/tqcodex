<?php

namespace App\Http\Middleware;

use Closure;
use Domain\Customer\Models\Contact;
use Domain\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectCustomerIfAuthenticated
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
        $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_CUSTOMER);
        $user = UserService::getUser(UserService::ROLE_TYPE_CUSTOMER);

        $isCustomer = false;
        if ($user && $user instanceof Contact && $guardObject->check() && ! $user->isAdmin()) {
            $isCustomer = true;
        }

        if ($isCustomer) {
            return Redirect::to(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER));
        }

        return $next($request);
    }
}
