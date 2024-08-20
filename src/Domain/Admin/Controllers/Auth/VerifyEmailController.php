<?php

namespace Domain\Admin\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\User;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request, $id, $hash): RedirectResponse
    {
        $user = User::find($id);

        if ($user && $user instanceof User && sha1($user->email) === $hash) {
            if ($user->hasVerifiedEmail()) {
                return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER).'?verified=1');
            }

            if ($user->markEmailAsVerified()) {
                /** @phpstan-ignore-next-line */
                event(new Verified($user));
            }
        }

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER).'?verified=1');
    }

    // public function __invoke_OLD(EmailVerificationRequest $request): RedirectResponse
    // {
    //     $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

    //     if ($user && $user instanceof User && $user->hasVerifiedEmail()) {
    //         return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN).'?verified=1');
    //     }

    //     if ($user && $user instanceof User && $user->markEmailAsVerified()) {
    //         event(new Verified($user));
    //     }

    //     return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN).'?verified=1');
    // }
}
