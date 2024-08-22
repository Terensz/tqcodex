<?php

namespace Domain\User\Services;

use Domain\Admin\Models\Admin;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Helpers\SessionHelper;
// use Domain\User\Passwords\PasswordBroker;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Lab404\Impersonate\Guard\SessionGuard as Lab404ImpersonateSessionGuard;

/**
 * Service for all types of roles, admins, customers and guests
 */
class UserService
{
    /**
     * Config
     */
    public const CONFIG_USE_ACCESS_TOKEN = 'USE_ACCESS_TOKEN';

    public const CONFIG = [
        self::CONFIG_USE_ACCESS_TOKEN => true,
    ];

    /**
     * Guard names
     */
    public const SESSION_NAME_USER_AUTH_GUARD = 'user_authGuard';

    public const SESSION_NAME_USER_ROLE_TYPE = 'user_roleType';

    /**
     * Role types
     */
    public const ROLE_TYPE_ADMIN = 'Admin';

    public const ROLE_TYPE_CUSTOMER = 'Customer';

    public const ROLE_TYPE_GUEST = 'Guest';

    public const ROUTE_PREFIXES = [
        self::ROLE_TYPE_ADMIN => 'admin',
        self::ROLE_TYPE_CUSTOMER => 'customer',
    ];

    public const LOGIN_ROUTES = [
        self::ROLE_TYPE_ADMIN => '/'.self::ROUTE_PREFIXES[self::ROLE_TYPE_ADMIN].'/login',
        self::ROLE_TYPE_CUSTOMER => '/'.self::ROUTE_PREFIXES[self::ROLE_TYPE_CUSTOMER].'/login',
    ];

    public const LOGOUT_ROUTES = [
        self::ROLE_TYPE_ADMIN => '/'.self::ROUTE_PREFIXES[self::ROLE_TYPE_ADMIN].'/logout',
        self::ROLE_TYPE_CUSTOMER => '/'.self::ROUTE_PREFIXES[self::ROLE_TYPE_CUSTOMER].'/logout',
    ];

    public const GUARD_ADMIN = 'admin_guard';

    public const GUARD_CUSTOMER = 'customer_guard';

    public const GUARD_PUBLIC = 'public_guard';

    public const GUARDS = [
        self::ROLE_TYPE_ADMIN => self::GUARD_ADMIN,
        self::ROLE_TYPE_CUSTOMER => self::GUARD_CUSTOMER,
        self::ROLE_TYPE_GUEST => self::GUARD_PUBLIC,
    ];

    public const AUTH_PROVIDER_ADMIN = 'admins';

    public const AUTH_PROVIDER_CUSTOMER = 'customers';

    public const AUTH_PROVIDERS = [
        self::ROLE_TYPE_ADMIN => self::AUTH_PROVIDER_ADMIN,
        self::ROLE_TYPE_CUSTOMER => self::AUTH_PROVIDER_CUSTOMER,
        self::ROLE_TYPE_GUEST => null,
    ];

    public const ROLE_TYPE_ENTITY_CLASSES = [
        self::ROLE_TYPE_ADMIN => Admin::class,
        self::ROLE_TYPE_CUSTOMER => Contact::class,
    ];

    public const DASHBOARD_ADDITIONAL_ROUTE_PART = [
        self::ROLE_TYPE_ADMIN => 'dashboard',
        self::ROLE_TYPE_CUSTOMER => 'dashboard',
    ];

    public const LOGOUT_ROUTE = 'logout';

    public static function getRoleTypeByGuard($requestedGuardName)
    {
        foreach (self::GUARDS as $roleType => $guardName) {
            if ($requestedGuardName === $guardName) {
                return $roleType;
            }
        }

        return null;
    }

    public static function getAdmin(): ?Admin
    {
        $user = self::getUser(UserService::ROLE_TYPE_ADMIN);

        return $user instanceof Admin ? $user : null;
    }

    public static function getContact(): ?Contact
    {
        $user = self::getUser(UserService::ROLE_TYPE_CUSTOMER);

        return $user instanceof Contact ? $user : null;
    }

    public static function getContactProfile(?Contact $contact = null): ?ContactProfile
    {
        if (! $contact) {
            $contact = self::getUser(UserService::ROLE_TYPE_CUSTOMER);
        }

        if (! $contact instanceof Contact) {
            return null;
        }

        $contactProfile = $contact->contactProfile()->first();
        if (! $contactProfile instanceof ContactProfile) {
            return null;
        }

        return $contactProfile;
    }

    // public static function getCurrentSessionGuard(string $roleType): ?StatefulGuard
    // {
    //     // $sessionUser = request()-
    //     return ! isset(UserService::GUARDS[$roleType]) ? null : Auth::guard(UserService::GUARDS[$roleType]);
    // }

    public static function getGuardObject(string $roleType): ?StatefulGuard
    {
        return ! isset(UserService::GUARDS[$roleType]) ? null : Auth::guard(UserService::GUARDS[$roleType]);
    }

    /**
     * Note: this method only works, when lab404/laravel-impersonate module is installed.
     */
    public static function quietLogin(string $roleType, Authenticatable $user)
    {
        $guardObject = self::getGuardObject($roleType);
        if ($guardObject instanceof Lab404ImpersonateSessionGuard) {
            $guardObject->quietLogin($user);
        }
    }

    public static function logoutGuard(string $roleType)
    {
        $guardObject = self::getGuardObject($roleType);
        if ($guardObject instanceof Lab404ImpersonateSessionGuard) {
            $guardObject->logoutCurrentDevice();
        }
    }

    // public static function getUser(string $roleType): ?Authenticatable
    public static function getUser(string $roleType): Admin|Contact|null
    {
        $guardObject = self::getGuardObject($roleType);

        return $guardObject ? $guardObject->user() : null;
    }

    public static function getRoutePrefix(string $roleType): string
    {
        return '/'.self::ROUTE_PREFIXES[$roleType];
    }

    public static function getHomeRouteBase(string $roleType): string
    {
        /**
         * @phpstan-ignore-next-line
         */
        return self::getRoutePrefix($roleType).(self::CONFIG[self::CONFIG_USE_ACCESS_TOKEN] ? '/{access_token}' : '');
    }

    public static function getHome(string $roleType): string
    {
        /**
         * @phpstan-ignore-next-line
         */
        return self::getRoutePrefix($roleType).(self::CONFIG[self::CONFIG_USE_ACCESS_TOKEN] ? '/'.AccessTokenService::getAccessToken($roleType) : '');
    }

    public static function getRouteParamArray($roleType, $additionalParams = []): array
    {
        /**
         * @phpstan-ignore-next-line
         */
        $params = UserService::CONFIG_USE_ACCESS_TOKEN ? ['access_token' => AccessTokenService::getAccessToken($roleType)] : [];
        if ($additionalParams !== []) {
            $params = array_merge($params, $additionalParams);
        }

        // dump($additionalParams);exit;
        // dump($params);exit;

        return $params;
    }

    public static function getDashboardRoute(string $roleType): string
    {
        return self::getHome($roleType).'/'.self::DASHBOARD_ADDITIONAL_ROUTE_PART[$roleType];
    }

    public static function getLoginRoute(string $roleType): string
    {
        return self::LOGIN_ROUTES[$roleType];
    }

    public static function getLogoutRoute(string $roleType): string
    {
        return self::LOGOUT_ROUTES[$roleType];
    }

    public static function getGuardName(string $roleType): ?string
    {
        return self::GUARDS[$roleType] ?? null;
    }

    public static function getAuthProvider(string $roleType): ?string
    {
        return self::AUTH_PROVIDERS[$roleType] ?? null;
    }

    public static function getGuardedUsers(): array
    {
        $result = [];
        foreach (UserService::GUARDS as $roleType => $guardName) {
            $result[$roleType] = UserService::getUser($roleType);
        }

        return $result;
    }

    // public static function registerSessionData2($roleType)
    // {
    //     $guardName = self::getGuardName($roleType);
    //     SessionHelper::set('user_authGuard', $guardName);
    //     SessionHelper::set('user_roleType', $roleType);
    // }

    /**
     * @todo: ezt felul kell vizsgalni!!!!
     * Nem latjuk, hogy mit csinal a $guardObject->check(...), es ez a megvaltozott Contact model mukodesevel szinkronban kell, hogy maradjon.
     */
    public static function registerSessionData()
    {
        $guardsConfig = UserService::GUARDS;
        foreach ($guardsConfig as $roleType => $guardName) {
            $guardObject = UserService::getGuardObject($roleType);
            if ($guardObject->check()) {
                SessionHelper::set(self::SESSION_NAME_USER_AUTH_GUARD, $guardName);
                SessionHelper::set(self::SESSION_NAME_USER_ROLE_TYPE, $roleType);
            }
        }
    }

    /**
     * The PasswordBroker is responsible for all the user providers required for the "password reset" event,
     * including the password reset token and the mailing. (Terence)
     * Please leave phpstan ignore below. Password::broker(...) will return \Illuminate\Contracts\Auth\PasswordBroker by default,
     * or the one has been set in the config/app.php .
     * In this case we have been set a custom one.
     */
    public static function getPasswordBroker(string $roleType): \Domain\User\Passwords\PasswordBroker
    {
        /** @phpstan-ignore-next-line */
        return Password::broker(UserService::getAuthProvider($roleType));
        // if ($roleType === self::ROLE_TYPE_ADMIN) {
        //     return Password::broker(UserService::getAuthProvider(UserService::ROLE_TYPE_ADMIN));
        // } elseif ($roleType === self::ROLE_TYPE_CUSTOMER) {
        //     $passwordBroker = self::registerCustomerPasswordBroker();
        //     return $passwordBroker;
        // } else {
        //     throw new Exception('No password broker for this role type: '.$roleType);
        // }
    }

    /**
     * Register the password broker instance.
     *
     * @return void
     */
    // public static function registerCustomerPasswordBroker()
    // {
    //     app()->singleton('auth.password', function ($app) {
    //         return new CustomerPasswordBrokerManager($app);
    //     });

    //     app()->bind('auth.password.broker', function ($app) {
    //         return $app->make('auth.password')->broker();
    //     });
    // }

    // public static function getGuardHomeBases(): array
    // {
    //     return [
    //         self::getAdminGuardName() => '/' . self::ROUTE_BASE_ADMIN,
    //         self::GUARD_CUSTOMER => '/' . self::ROUTE_BASE_CUSTOMER
    //     ];
    // }

    // public static function getAdminGuardName(): string|null
    // {
    //     $configuredGuards = Config::get('auth.guards');
    //     $configuredProviders = Config::get('auth.providers');
    //     foreach ($configuredGuards as $configuredGuard => $configuredGuardSettings) {
    //         foreach ($configuredProviders as $configuredProvider => $configuredProviderSettings) {
    //             if ($configuredGuardSettings['provider'] === $configuredProvider) {
    //                 if ($configuredProviderSettings['model'] === self::ENTITY_CLASS_ADMIN) {
    //                     return $configuredGuard;
    //                 }
    //             }
    //         }
    //     }

    //     return null;
    // }

    // public static function getHome(string $guard = self::DEFAULT_GUARD): string
    // {
    //     if ($guard === self::DEFAULT_GUARD) {
    //         $configuredDefaultGuard = Config::get('auth.defaults.guard');
    //         // if (!isset(self::GUARD_HOME_BASES))
    //         $guard = $configuredDefaultGuard;
    //     }

    //     return self::GUARD_HOME_BASES[$guard] . '/' . AccessTokenService::getAccessToken($guard);
    // }
}
