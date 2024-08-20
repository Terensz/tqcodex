<?php

namespace Domain\Customer\Services;

use Domain\Customer\Models\Organization;
use Domain\User\Services\UserService;

class OrganizationService
{
    public const NAME_MAX_DIGITS = 127;

    public const TAXPAYER_ID_MAX_DIGITS = 13;

    public static function getContactProfiles(?Organization $organization = null)
    {
        return ! $organization ? null : $organization->contactProfiles()->get();
    }

    public static function getOrganizationBindings(?Organization $organization = null): array
    {
        $currentContactBinds = false;
        $otherBindingContactProfiles = [];
        if ($organization) {
            $contactProfiles = $organization->contactProfiles()->get();
            foreach ($contactProfiles as $contactProfile) {
                if (UserService::getContact() && UserService::getContact()->getContactProfile() && UserService::getContact()->getContactProfile()->id === $contactProfile->id) {
                    $currentContactBinds = true;
                } else {
                    $otherBindingContactProfiles[] = $contactProfile;
                }
            }
        }

        return [
            'currentContactBinds' => $currentContactBinds,
            'otherBindingContactProfiles' => $otherBindingContactProfiles,
        ];
    }

    public static function getCurrentContactsOrganizations() {}
}
