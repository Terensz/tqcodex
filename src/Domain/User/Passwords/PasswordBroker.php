<?php

namespace Domain\User\Passwords;

use Closure;
use Domain\Admin\Models\Admin;
use Domain\Customer\Models\Contact;
use Illuminate\Auth\Passwords\PasswordBroker as BasePasswordBroker;
// use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
// use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use UnexpectedValueException;

class PasswordBroker extends BasePasswordBroker
{
    /**
     * Create a new password broker instance.
     *
     * @param  \Illuminate\Auth\Passwords\TokenRepositoryInterface  $tokens
     * @param  \Illuminate\Contracts\Auth\UserProvider  $users
     * @param  \Illuminate\Contracts\Events\Dispatcher  $users
     * @return void
     */
    // public function __construct(TokenRepositoryInterface $tokens, UserProvider $users, ?Dispatcher $dispatcher = null)
    // {
    //     $this->users = $users;
    //     $this->tokens = $tokens;
    //     $this->events = $dispatcher;
    // }

    /**
     * Send a password reset link to a user.
     *
     * @return string
     */
    public function sendResetLink(array $credentials, ?Closure $callback = null)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        //        if (is_null($user)) {
        //            return static::INVALID_USER;
        //        }

        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        if ($user && $user instanceof Contact && $user->getContactProfile()) {
            $user->sendPasswordResetNotification(
                $this->tokens->create($user->getContactProfile())
            );
        }
        if ($user && $user instanceof Admin) {
            $user->sendPasswordResetNotification(
                $this->tokens->create($user)
            );
        }

        return static::RESET_LINK_SENT;
    }

    /**
     * Reset the password for the given token.
     *
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        if (! $user instanceof CanResetPasswordContract) {
            // dump($user);
            // dump('not instance!!!');exit;
            return $user;
        }

        $password = $credentials['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);

        $this->tokens->delete($user);

        return static::PASSWORD_RESET;
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @return \Illuminate\Contracts\Auth\CanResetPassword|string
     */
    protected function validateReset(array $credentials)
    {
        // dump($credentials);
        $user = $this->getUser($credentials);
        if (is_null($user) || ! $user instanceof Contact || ! $user->getContactProfile()) {
            return static::INVALID_USER;
        }

        // dump($user);exit;

        // dump($this->tokens);

        /**
         * $this->tokens :
         *     #tokens: Illuminate\Auth\Passwords\DatabaseTokenRepository {#1775 ▼
         *         #connection: Illuminate\Database\MySqlConnection {#792 …25}
         *             #hasher: Illuminate\Hashing\HashManager {#779 ▶}
         *                 #table: "contact_tokens"
         *                 #hashKey:
         *                 #expires: 3600
         *                 #throttle: 60
         */
        if (! $this->tokens->exists($user->getContactProfile(), $credentials['token'])) {
            // dump($this->tokens);exit;
            return static::INVALID_TOKEN;
        }

        // dump($user);

        return $user;
    }

    /**
     * Get the user for the given credentials.
     *
     * @return \Illuminate\Contracts\Auth\CanResetPassword|null
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        /**
         *  $this->users :
         *      #users: Illuminate\Auth\EloquentUserProvider
         *          #model: "Domain\Customer\Models\Contact"
         */
        $user = $this->users->retrieveByCredentials($credentials);

        // dump($user);exit;

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }
}
