<?php

namespace Domain\Admin\Controllers;

use Domain\Admin\Controllers\Base\BaseAdminController;
use Domain\Admin\Models\User;
use Domain\Admin\Models\UserToken;
use Domain\Admin\Requests\ProfileUpdateRequest;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\TokenHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends BaseAdminController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_BASIC_ADMIN_INTERFACE;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return $this->renderContent($request, 'admin.profile.edit', __('user.EditProfile'), [
            'user' => UserService::getUser(UserService::ROLE_TYPE_ADMIN),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // dump('update!!!');
        $additionalSessionMessages = [];
        $user = UserService::getAdmin();
        if ($user) {
            $user->fill($request->validated());

            /**
             * @todo: Istvántól meg kell kérdezni, hogy: ez miért volt benne
             */
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $originalEmail = $user->getOriginal()['email'];
            $modifiedEmail = $user->email;

            if ($originalEmail !== $modifiedEmail) {
                $existingUserToken = UserToken::where(['email' => $modifiedEmail, 'token_type' => UserToken::TOKEN_TYPE_EMAIL_CHANGE])->first();
                if ($existingUserToken) {
                    $existingUserToken->delete();
                }

                $token = TokenHelper::generateToken();
                $userToken = new UserToken;
                $userToken->email = $modifiedEmail;
                $userToken->token = $token;
                $userToken->token_type = UserToken::TOKEN_TYPE_EMAIL_CHANGE;
                $userToken->save();

                $user->sendEmailChangeRequestNotification($token, $originalEmail, $modifiedEmail);

                $user->email = $originalEmail;

                $additionalSessionMessages[] = [
                    'type' => 'success',
                    'message' => __('user.EmailChangeRequested'),
                ];
                // $request->session()->put('success', __('user.EmailChangeRequested'));
            }

            $user->save();
        }

        $return = Redirect::route('admin.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_ADMIN)])->with('status', __('user.ProfileBaseDataSaved'));
        foreach ($additionalSessionMessages as $additionalSessionMessage) {
            $return->with($additionalSessionMessage['type'], $additionalSessionMessage['message']);
        }

        return $return;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = UserService::getUser(UserService::ROLE_TYPE_ADMIN);
        if ($user instanceof User) {
            UserService::getGuardObject(UserService::ROLE_TYPE_ADMIN)->logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return Redirect::to(route('home'));
    }
}
