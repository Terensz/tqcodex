<?php

namespace Domain\Customer\Requests\Auth;

use Domain\Customer\Models\Contact;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Events\FailedAuth;
use Domain\User\Services\AccessTokenService;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // dump('authenticate');exit;
        self::ensureIsNotRateLimited($this->input('email'), $this->ip(), $this);

        $contact = self::findValidContact($this->input('email'), $this->input('password'), $this->ip());

        // dump($contact);exit;

        if ($contact instanceof Contact) {
            $guardObject = UserService::getGuardObject(UserService::ROLE_TYPE_CUSTOMER);
            $guardObject->login($contact);
            if (! $guardObject->user()) {
                self::handleFailedAuth($this->input('email'), $this->input('password'), $this->ip());
            }

            $accessToken = AccessTokenService::createAccessToken();
            AccessTokenService::setAccessToken(UserService::ROLE_TYPE_CUSTOMER, $accessToken);

            RateLimiter::clear(self::getThrottleKey($this->input('email'), $this->ip()));
        }
    }

    /**
     * This static method can be used instead of $guardObject->validate() .
     */
    public static function findValidContact(?string $emailInput, ?string $passwordInput, ?string $ip)
    {
        $contact = Contact::whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, $emailInput)->first();

        if (! $contact || ! Hash::check($passwordInput, $contact->password)) {
            self::handleFailedAuth($emailInput, $passwordInput, $ip);
        }

        return $contact;
    }

    public static function handleFailedAuth(string $emailInput, string $passwordInput, string $ip)
    {
        RateLimiter::hit(self::getThrottleKey($emailInput, $ip));
        FailedAuth::dispatch($passwordInput);

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function ensureIsNotRateLimited($emailInput, $ip, Request $request): void
    {
        if (! RateLimiter::tooManyAttempts(self::getThrottleKey($emailInput, $ip), 5)) {
            return;
        }

        /**
         * This event is fired when a user attempts too many login failures.
         */
        event(new Lockout($request));
        // event(new Lockout($customerLoginRequestObject));

        $seconds = RateLimiter::availableIn(self::getThrottleKey($emailInput, $ip));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public static function getThrottleKey(string $emailInput, string $ip): string
    {
        return Str::transliterate(Str::lower($emailInput).'|'.$ip);
    }
}
