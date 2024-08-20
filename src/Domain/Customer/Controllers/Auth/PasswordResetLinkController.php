<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * Route (get): 'customer/forgot-password'
     * Name: 'customer.password.request'
     *
     * This route enters the scene when the customer manually calls this link, or when they click on the link at the login screen.
     * This route can be called when not logged in.
     */
    public function create(): View
    {
        return view('customer.auth.forgot-password');
    }

    /**
     * Route (post): 'customer/forgot-password'
     * Name: customer.password.email
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = UserService::getPasswordBroker(UserService::ROLE_TYPE_CUSTOMER)->sendResetLink(
            // $status = Password::broker('contacts')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
