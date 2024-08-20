<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Customer\Requests\Auth\CustomerLoginRequest;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE;
    }

    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('customer.auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // $user = UserService::getUser(UserService::ROLE_TYPE_CUSTOMER);
        $contactProfile = UserService::getContactProfile();
        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $contact = CustomerLoginRequest::findValidContact($contactProfile->email, $request->password, $request->ip);

        if (! $contact) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER));
    }
}
