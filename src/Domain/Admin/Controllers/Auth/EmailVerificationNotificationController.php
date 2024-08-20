<?php

namespace Domain\Admin\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\User;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        if (! $user instanceof User || ! $user->hasVerifiedEmail()) {
            return back()->with('status', 'verification-link-sent');
        }

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN));
    }

    // public function store_OLD(Request $request): RedirectResponse
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN));
    //     }

    //     $request->user()->sendEmailVerificationNotification();

    //     return back()->with('status', 'verification-link-sent');
    // }
}
