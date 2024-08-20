<?php

namespace Domain\Admin\Controllers\Auth;

use Domain\Admin\Requests\Auth\AdminLoginRequest as LoginRequest;
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
    public function login(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function auth(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        /**
         * Only successful login can reach this code row.
         * Failed login will throw an exception.
         */
        $request->session()->regenerate();
        SessionHelper::set('auth_guard', UserService::getGuardName(UserService::ROLE_TYPE_ADMIN));

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN));
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        UserService::logoutGuard(UserService::ROLE_TYPE_ADMIN);

        return redirect(route('admin.login'));
    }
}
