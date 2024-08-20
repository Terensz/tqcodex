<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Admin\Rules\UserRules;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Rules\ValidCustomerPasswordResetToken;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewPasswordController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * route: 'customer/reset-password/{token}'
     * name: 'customer.password.reset'
     *
     * This route enters the scene after the customer clicked onto the link they received in e-mail.
     * Token will be validated after submitting the form, token messages will be placed to the e-mail messages.
     */
    public function create(Request $request): View
    {
        // dump('reset');exit;
        return view('customer.auth.reset-password', ['request' => $request]);
    }

    /**
     * route: 'customer/reset-password'
     * name: 'customer.password.store'
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // dump('reset');exit;
        // Alma5167429Alma
        $requestData = $request->only('email', 'password', 'password_confirmation', 'token');

        $contactProfile = isset($requestData['email']) ? ContactProfile::where('email', $requestData['email'])->first() : null;

        // $contactProfile = UserService::getContactProfile($contact);

        if (! $contactProfile || ! $contactProfile->getContact()) {
            abort(403, 'customer.CustomerNotFound');
        }

        $rulesConfig = UserRules::rules();

        $rules = [
            'token' => ['required'],
            'email' => ['required', 'email', new ValidCustomerPasswordResetToken($contactProfile, $requestData['token'])],
            // 'email' => ['required', 'email'],
            'password' => $rulesConfig['technicalPassword'],
        ];

        try {
            $request->validate($rules);
        } catch (\Exception $e) {
            // dump($request);
            // dump($rules);
            // dump($e);exit;
            throw $e;
        }

        // dump('hello');exit;

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->getPasswordResetStatus($request, $requestData, true);

        // dump($status);
        // dump('hello');exit;

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    /**
     * Reset method:
     * - validates password
     * Force fills password and remember_token from
     */
    public function getPasswordResetStatus(Request $request, $requestData, bool $saveContact)
    {
        // - checks if user exists
        //
        $passwordResetStatus = UserService::getPasswordBroker(UserService::ROLE_TYPE_CUSTOMER)->reset(
            $requestData,
            function ($contact) use ($request, $saveContact) {
                $contact->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ]);

                if ($saveContact) {
                    $contact->save();
                    event(new PasswordReset($contact));
                }
            }
        );

        // dump($passwordResetStatus);

        return $passwordResetStatus;
    }
}
