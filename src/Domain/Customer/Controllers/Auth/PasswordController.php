<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Admin\Rules\AdminRules;
use Domain\Customer\Models\Contact;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Rules\CurrentPassword;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE;
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // dump('helo');exit;
        $validationRulesArray = [
            'current_password' => ['required', new CurrentPassword(UserService::getContact(), $request->current_password)],
            'password' => AdminRules::rules()['technicalPassword'],
        ];

        $validated = $request->validateWithBag('updatePassword', $validationRulesArray);

        $user = UserService::getUser(UserService::ROLE_TYPE_CUSTOMER);

        if ($user && $user instanceof Contact) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        return back()->with('success', __('user.PasswordUpdated'));
    }
}
