<?php

namespace Domain\User\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * Destroys an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        foreach (UserService::GUARDS as $roleType => $guard) {
            Auth::guard($guard)->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('project.homepage'));
    }
}
