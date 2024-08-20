<?php

namespace App\Http\Middleware;

use Closure;
use Domain\Customer\Models\Contact;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Lab404\Impersonate\Guard\SessionGuard;

class AuthenticateCustomer extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_CUSTOMER);
        $contact = UserService::getContact();

        if ($contact instanceof Contact && $contact->getContactProfile()) {
            $validContact = Contact::whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, $contact->getContactProfile()->email)->first();
            if (! $validContact) {
                return redirect(UserService::getLogoutRoute(UserService::ROLE_TYPE_CUSTOMER));
            }

            if ($guardObject->check()) {
                return $next($request);
            }
        }

        return redirect(UserService::getLoginRoute(UserService::ROLE_TYPE_CUSTOMER));
    }

    // public function handle($request, Closure $next, ...$guards)
    // {
    //     $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_CUSTOMER);
    //     $contact = $guardObject ? $guardObject->getUser() : null;

    //     if ($guardObject instanceof SessionGuard && $guardObject->getUser() instanceof Contact && $guardObject->getUser()->getContactProfile()) {
    //         $validContact = Contact::whereEmailIs($guardObject->getUser()->getContactProfile()->email)->first();
    //         if (! $validContact) {
    //             return redirect(UserService::getLogoutRoute(UserService::ROLE_TYPE_CUSTOMER));
    //         }

    //         if ($guardObject->check()) {
    //             return $next($request);
    //         }
    //     }

    //     return redirect(UserService::getLoginRoute(UserService::ROLE_TYPE_CUSTOMER));
    // }

    // public function handle($request, Closure $next, ...$guards)
    // {
    //     $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_CUSTOMER);
    //     if ($guardObject && $guardObject->check()) {
    //         return $next($request);
    //     } else {
    //         return redirect(UserService::getLoginRoute(UserService::ROLE_TYPE_CUSTOMER));
    //     }
    // }
}
