<?php

namespace Domain\Shared\Listeners;

use Domain\User\Events\ActivityLogRequested;
use Domain\User\Events\FailedAuth;
use Domain\User\Services\UserService;

class CreateFailedAuthActivityLog
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(?FailedAuth $event = null): void
    {
        $failedPassword = $event->failedPassword;

        /**
         * Checking each and every guards if they authenticated a user.
         */
        $userAuthFound = false;
        foreach (UserService::getGuardedUsers() as $roleType => $userModel) {
            if ($userModel && in_array($roleType, [UserService::ROLE_TYPE_ADMIN, UserService::ROLE_TYPE_CUSTOMER])) {
                $userAuthFound = true;
                /**
                 * We are making a log entry for each and every guards which authenticated us.
                 */
                $this->dispatchActivityLogRequested($roleType, $failedPassword, $userModel);
            }
        }

        /**
         * If we are not logged as an admin or a customer, we make a log entry anyway.
         */
        if (! $userAuthFound) {
            $this->dispatchActivityLogRequested(UserService::ROLE_TYPE_GUEST, $failedPassword, null);
        }
    }

    public function dispatchActivityLogRequested($roleType, $failedPassword, $userModel = null)
    {
        ActivityLogRequested::dispatch(
            $roleType,
            $userModel,
            CreateActivityLog::ACTION_AUTH_FAILED,
            null,
            $failedPassword,
            null
        );
    }
}
