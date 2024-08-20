<?php

namespace Domain\User\Passwords;

use Domain\User\Services\UserService;
use Illuminate\Auth\Passwords\PasswordBroker as FrameworkPasswordBroker;
use Illuminate\Auth\Passwords\PasswordBrokerManager as BasePasswordBrokerManager;
use InvalidArgumentException;

class PasswordBrokerManager extends BasePasswordBrokerManager
{
    /**
     * Resolve the given broker.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException(
                "Password resetter [{$name}] is not defined."
            );
        }

        /**
         * $this->app['auth']: Illuminate\Auth\AuthManager
         */

        // The password broker uses a token repository to validate tokens and send user
        // password e-mails, as well as validating that password reset process as an
        // aggregate service of sorts providing a convenient interface for resets.
        // dump($name);exit;

        if ($name === UserService::AUTH_PROVIDER_ADMIN) {
            return new FrameworkPasswordBroker(
                parent::createTokenRepository($config),
                $this->app['auth']->createUserProvider($config['provider'] ?? null)
            );
        }

        return new PasswordBroker(
            $this->createTokenRepository($config),
            $this->app['auth']->createUserProvider($config['provider'] ?? null)
        );
    }

    /**
     * Create a token repository instance based on the given configuration.
     *
     * @return \Illuminate\Auth\Passwords\TokenRepositoryInterface
     */
    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $connection = $config['connection'] ?? null;

        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire'],
            $config['throttle'] ?? 0
        );
    }
}
