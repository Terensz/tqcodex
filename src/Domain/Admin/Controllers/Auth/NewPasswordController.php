<?php

namespace Domain\Admin\Controllers\Auth;

use Domain\Admin\Models\Admin;
use Domain\Admin\Rules\AdminRules;
use Domain\Admin\Rules\ValidAdminPasswordResetToken;
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
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // dump('alma');exit;
        // dump($request);exit;
        return view('admin.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $requestData = $request->only('email', 'password', 'password_confirmation', 'token');

        $user = Admin::where('email', $requestData['email'])->first();

        // dump($user);exit;

        $rulesConfig = AdminRules::rules();

        $rules = [
            'token' => ['required'],
            'email' => ['required', 'email', new ValidAdminPasswordResetToken($user instanceof Admin ? $user : null, $requestData['token'])],
            // 'email' => ['required', 'email'],
            'password' => $rulesConfig['technicalPassword'],
        ];

        $request->validate($rules);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->getPasswordResetStatus($request, $requestData, true);

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('admin.login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    public function getPasswordResetStatus(Request $request, $requestData, bool $saveUser)
    {
        return Password::broker(UserService::getAuthProvider(UserService::ROLE_TYPE_ADMIN))->reset(
            // return Password::reset(
            $requestData,
            function ($user) use ($request, $saveUser) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ]);

                if ($saveUser) {
                    $user->save();
                    event(new PasswordReset($user));
                }
            }
        );
    }
}
