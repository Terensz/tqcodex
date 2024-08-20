<?php

namespace Domain\Customer\Services;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfileOrganization;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\User\Services\UserService;

class CustomerService
{
    public static function getOrganizations(?Contact $contact = null): array
    {
        $organizations = [];
        $contactProfile = null;

        if (! $contact) {
            $contact = UserService::getContact();
            $contactProfile = $contact->getContactProfile();
        }

        if (! $contactProfile) {
            return $organizations;
        }

        $contactProfileOrganizations = ContactProfileOrganization::where(['contact_profile_id' => $contactProfile->id])->get();

        foreach ($contactProfileOrganizations as $contactProfileOrganization) {
            $organization = $contactProfileOrganization->organization()->first();
            $organizations[] = $organization;
            // $organizations[] = [
            //     ValidationHelper::OPTION_LABEL => $organization->name,
            //     ValidationHelper::OPTION_VALUE => $organization->id
            // ];
        }
        // dump($organizations);exit;

        return $organizations;
    }
}
