<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * route: customer.verification.send
     * url: /email/verification-notification
     */
    public function store(Request $request): RedirectResponse
    {
        $contactProfile = UserService::getContactProfile();

        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        if (! $contactProfile->hasVerifiedEmail()) {
            $contactProfile->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        }

        abort(403, 'user.RegistrationVerifiedAlready');
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
