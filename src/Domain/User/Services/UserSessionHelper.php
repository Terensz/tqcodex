<?php

namespace Domain\User\Services;

use Domain\Shared\Helpers\SessionHelper;

/**
 * Service for
 */
class UserSessionHelper
{
    public const SESSION_KEY_AUTH_GUARD = 'auth_guard';

    public static function putAuthGuard(string $roleType): void
    {
        SessionHelper::set(self::SESSION_KEY_AUTH_GUARD, UserService::getGuardName($roleType));
    }

    public static function getAuthGuard(): ?string
    {
        return SessionHelper::get(self::SESSION_KEY_AUTH_GUARD);
    }
}
