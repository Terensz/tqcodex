<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * route: customer.verification.notice
     * url: /verify-email
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $contactProfile = UserService::getContactProfile();

        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        if ($contactProfile->hasVerifiedEmail()) {
            abort(403, 'user.RegistrationVerifiedAlready');
        }

        return view('customer.auth.verify-email');

        // return $contactProfile->getContact()->hasVerifiedEmail()
        //     ? redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER))
        //     : view('customer.auth.verify-email');
    }
}
