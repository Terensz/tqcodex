<?php

namespace Domain\Admin\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\Admin;
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
        $admin = Admin::find($id);

        if ($admin && $admin instanceof Admin && sha1($admin->email) === $hash) {
            if ($admin->hasVerifiedEmail()) {
                return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER).'?verified=1');
            }

            if ($admin->markEmailAsVerified()) {
                /** @phpstan-ignore-next-line */
                event(new Verified($admin));
            }
        }

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER).'?verified=1');
    }
}
