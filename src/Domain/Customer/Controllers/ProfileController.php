<?php

namespace Domain\Customer\Controllers;

use Domain\Customer\Controllers\Base\BaseCustomerController;
use Domain\Customer\Models\ContactToken;
use Domain\Customer\Requests\ProfileUpdateRequest;
use Domain\Customer\Rules\ContactRules;
use Domain\Customer\Rules\IsCurrentCustomerPassword;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\TokenHelper;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\ImageService;
use Domain\User\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileController extends BaseCustomerController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE;
    }

    /**
     * Display the user's profile form.
     *
     * route: customer(/accessToken)/profile/edit
     * route name: customer.profile.edit
     */
    public function edit(Request $request): View
    {
        // dump(UserService::getPasswordBroker(UserService::ROLE_TYPE_CUSTOMER));
        // dump(UserService::getPasswordBroker(UserService::ROLE_TYPE_ADMIN));
        // exit;

        $contactProfile = UserService::getContactProfile();
        if (! $contactProfile) {
            abort(403, 'customer.CustomerNotFound');
        }

        return $this->renderContent($request, 'customer.profile.edit', __('user.EditProfile'), [
            'contactProfile' => $contactProfile,
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * route: customer(/{accessToken})/profile/update
     * route name: customer.profile.update
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // dump('edit');exit;
        $contactProfile = UserService::getContactProfile();
        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $additionalSessionMessages = [];

        $contactProfile->fill($request->validated());

        /**
         * @todo: Istvántól meg kell kérdezni, hogy: ez miért volt benne
         */
        if ($contactProfile->isDirty('email')) {
            $contactProfile->getContact()->email_verified_at = null;
        }

        $originalEmail = $contactProfile->getOriginal()['email'];
        $modifiedEmail = $contactProfile->email;

        if ($originalEmail !== $modifiedEmail) {
            // dump('email changed!');
            $existingUserToken = ContactToken::where(['email' => $modifiedEmail, 'token_type' => ContactToken::TOKEN_TYPE_EMAIL_CHANGE])->first();
            if ($existingUserToken) {
                $existingUserToken->delete();
            }

            $token = TokenHelper::generateToken();
            $userToken = new ContactToken;
            $userToken->email = $modifiedEmail;
            $userToken->token = $token;
            $userToken->token_type = ContactToken::TOKEN_TYPE_EMAIL_CHANGE;
            $userToken->save();

            // dump($userToken);exit;

            $contactProfile->sendEmailChangeRequestNotification($token, $originalEmail, $modifiedEmail);

            $contactProfile->email = $originalEmail;

            $additionalSessionMessages[] = [
                'type' => 'success',
                'message' => __('user.EmailChangeRequested'),
            ];
            // $request->session()->put('success', __('user.EmailChangeRequested'));
        }

        $contactProfile->save();

        $return = Redirect::route('customer.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)])->with('status', __('user.ProfileBaseDataSaved'));
        foreach ($additionalSessionMessages as $additionalSessionMessage) {
            $return->with($additionalSessionMessage['type'], $additionalSessionMessage['message']);
        }

        return $return;
    }

    /**
     * route name: customer.profile.destroy
     */
    public function destroy(Request $request): RedirectResponse
    {
        $contactProfile = UserService::getContactProfile();
        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', new IsCurrentCustomerPassword],
        ]);

        UserService::getGuardObject(UserService::ROLE_TYPE_CUSTOMER)->logout();

        ImageService::removeImage($contactProfile->profile_image);
        $contactProfile->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to(route('home'));
    }

    /**
     * route: customer(/accessToken)/profile/uploadImage
     * route name: customer.profile.uploadImage
     */
    public function uploadImage(Request $request): RedirectResponse
    {
        $contactProfile = UserService::getContactProfile();
        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $request->validate([
            'profile_image' => ContactRules::rules()['profile_image'],
        ]);
        $imagePath = $request->file('profile_image')->store('customer_profile_images', 'public');

        if ($contactProfile->profile_image) {
            ImageService::removeImage($contactProfile->profile_image);
        }
        $contactProfile->profile_image = $imagePath;
        $contactProfile->save();

        return Redirect::to(route('customer.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]))->with('success', __('user.ProfilePhotoUploadedSuccessfully'));
    }

    /**
     * route: customer(/accessToken)/profile/showImage
     * route name: customer.profile.showImage
     */
    public function showImage(Request $request)
    {
        $contactProfile = UserService::getContactProfile();
        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $imagePath = $contactProfile->profile_image;
        // dump($imagePath);exit;

        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return new BinaryFileResponse(storage_path('app/public/'.$imagePath));
        }

        abort(404, 'Image not found');
    }

    // public static function removeImage($imagePath)
    // {
    //     if ($imagePath && Storage::disk('public')->exists($imagePath)) {
    //         Storage::disk('public')->delete($imagePath);
    //     }
    // }
}
