<?php

namespace Domain\Admin\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    // public function show(): View
    // {
    //     // dump('ALMA!!!');exit;
    //     return view('admin.auth.confirm-password');
    // }

    /**
     * Confirm the user's password.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);
    //     if ($user instanceof User) {
    //         if (! UserService::getGuardObject(UserService::ROLE_TYPE_ADMIN)->validate([
    //             'email' => $user->email,
    //             'password' => $request->password,
    //         ])) {
    //             throw ValidationException::withMessages([
    //                 'password' => __('auth.password'),
    //             ]);
    //         }

    //         $request->session()->put('auth.password_confirmed_at', time());
    //     }

    //     return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_ADMIN));
    // }
}
