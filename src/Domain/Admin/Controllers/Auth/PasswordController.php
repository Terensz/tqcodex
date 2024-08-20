<?php

namespace Domain\Admin\Controllers\Auth;

use Domain\Admin\Models\User;
use Domain\Admin\Rules\UserRules;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Rules\CurrentPassword;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validationRulesArray = [
            'current_password' => ['required', new CurrentPassword(UserService::getAdmin(), $request->current_password)],
            'password' => UserRules::rules()['technicalPassword'],
        ];

        $validated = $request->validateWithBag('updatePassword', $validationRulesArray);

        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        if ($user && $user instanceof User) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        return back()->with('success', __('user.PasswordUpdated'));
    }

    // public function update(Request $request): RedirectResponse
    // {
    //     $validationRulesArray = [
    //         'current_password' => ['required', 'current_password'],
    //         'password' => UserRules::rules()['technicalPassword'],
    //     ];

    //     $validated = $request->validateWithBag('updatePassword', $validationRulesArray);

    //     $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

    //     if ($user && $user instanceof User) {
    //         $user->password = Hash::make($validated['password']);
    //         $user->save();
    //     }

    //     return back()->with('status', 'password-updated');
    // }
}
