<?php

namespace Domain\Admin\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\Admin;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        return $user && $user instanceof Admin && $user->hasVerifiedEmail()
            ? redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN))
            : view('admin.auth.verify-email');
    }
}
