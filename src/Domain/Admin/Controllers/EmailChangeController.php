<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Models\User;
use Domain\Admin\Models\UserToken;
use Domain\Admin\Rules\ValidAdminEmailChangeToken;
use Domain\Admin\Rules\ValidAdminNewEmail;
use Domain\Admin\Rules\ValidAdminOldEmail;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EmailChangeController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('admin.profile.change-email', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $requestData = $request->only('old_email', 'email', 'password', 'token');

        // $token = TokenHelper::decodeToken($requestData['token']);
        $token = $requestData['token'];
        $oldEmail = $requestData['old_email'];
        $newEmail = $requestData['email'];
        $userToken = UserToken::where('token', $token)->first();
        $user = User::where('email', $oldEmail)->first();

        if ($user instanceof User) {
            $rules = [
                'token' => ['required'],
                'old_email' => ['required', 'email', new ValidAdminEmailChangeToken($user, $userToken, $newEmail), new ValidAdminOldEmail($user, $oldEmail)],
                'email' => ['required', 'email', new ValidAdminNewEmail($userToken, $newEmail)],
            ];

            $request->validate($rules);

            $user->email = $newEmail;
            $user->email_verified_at = now();
            $user->save();
            DB::table('user_tokens')
                ->where('email', $newEmail)
                ->where('token', $token)
                ->delete();
        }

        return Redirect::to(route('admin.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)]))->with('success', __('user.EmailAddressSuccessfullyChanged'));
    }
}
