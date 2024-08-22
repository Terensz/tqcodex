<?php

namespace Domain\Admin\Controllers\Auth;

use Domain\Admin\Models\Admin;
use Domain\Admin\Rules\AdminRules;
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
            'password' => AdminRules::rules()['technicalPassword'],
        ];

        $validated = $request->validateWithBag('updatePassword', $validationRulesArray);

        $admin = UserService::getUser(UserService::ROLE_TYPE_ADMIN);

        if ($admin && $admin instanceof Admin) {
            $admin->password = Hash::make($validated['password']);
            $admin->save();
        }

        return back()->with('success', __('user.PasswordUpdated'));
    }
}
