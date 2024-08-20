<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Customer\Requests\Auth\CustomerLoginRequest as LoginRequest;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\SessionHelper;
use Domain\User\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticatedSessionController extends BaseContentController
{
    use AuthenticatesUsers;

    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('customer.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $loginRequest): RedirectResponse
    {
        $loginRequest->authenticate();

        /**
         * Only successful login can reach this code row.
         * Failed login will throw an exception.
         */
        $loginRequest->session()->regenerate();
        SessionHelper::set('auth_guard', UserService::getGuardName(UserService::ROLE_TYPE_CUSTOMER));

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER));
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        UserService::logoutGuard(UserService::ROLE_TYPE_CUSTOMER);

        return redirect(route('customer.login'));
    }
}
