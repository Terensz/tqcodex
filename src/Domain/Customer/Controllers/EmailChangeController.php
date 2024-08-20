<?php

namespace Domain\Customer\Controllers;

use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\ContactToken;
use Domain\Customer\Rules\ValidCustomerEmailChangeToken;
use Domain\Customer\Rules\ValidCustomerNewEmail;
use Domain\Customer\Rules\ValidCustomerOldEmail;
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
        return view('customer.profile.change-email', ['request' => $request]);
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
        $userToken = ContactToken::where('token', $token)->first();

        // dump($userToken);
        // dump($requestData);exit;

        $contactProfile = ContactProfile::where('email', $oldEmail)->first();
        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $rules = [
            'token' => [
                'required',
            ],
            'old_email' => [
                'required',
                'email',
                new ValidCustomerEmailChangeToken($userToken, $newEmail),
                new ValidCustomerOldEmail($contactProfile, $oldEmail)],
            'email' => [
                'required',
                'email',
                new ValidCustomerNewEmail($userToken, $newEmail),
            ],
            // 'password' => $rulesConfig['technicalPassword'],
        ];

        $request->validate($rules);

        $contactProfile->email = $newEmail;
        $contactProfile->save();
        $contactProfile->getContact()->email_verified_at = now();
        $contactProfile->getContact()->save();

        DB::table('contact_tokens')
            ->where('email', $newEmail)
            ->where('token', $token)
            ->delete();

        // return redirect()->route('customer.dashboard');
        return Redirect::to(route('customer.profile.edit', ['access_token' => AccessTokenService::getAccessToken(UserService::ROLE_TYPE_CUSTOMER)]))->with('success', __('user.EmailAddressSuccessfullyChanged'));

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        // $status = $this->getPasswordResetStatus($request, $requestData, true);

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        // return $status === Password::PASSWORD_RESET
        //     ? redirect()->route('login')->with('status', __($status))
        //     : back()->withInput($request->only('email'))
        //         ->withErrors(['email' => __($status)]);
    }

    // public function getPasswordResetStatus(Request $request, $requestData, bool $saveContact)
    // {
    //     return Password::broker(UserService::getAuthProvider(UserService::ROLE_TYPE_CUSTOMER))->reset(
    //         $requestData,
    //         function ($contact) use ($request, $saveContact) {
    //             $contact->forceFill([
    //                 'password' => Hash::make($request->password),
    //                 'remember_token' => Str::random(60),
    //             ]);

    //             if ($saveContact) {
    //                 $contact->save();
    //                 event(new PasswordReset($contact));
    //             }
    //         }
    //     );
    // }
}
