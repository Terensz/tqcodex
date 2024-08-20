<?php

namespace Domain\Customer\Services;

use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\ContactProfileOrganization;
use Domain\Customer\Models\Organization;

class ContactProfileOrganizationSeederService
{
    public $registeredContactProfileOrganizations = [];

    public function __construct()
    {
        $this->registerExistingContactProfileOrganizations();
    }

    public function registerExistingContactProfileOrganizations()
    {
        $contactProfileOrganizations = ContactProfileOrganization::all();

        foreach ($contactProfileOrganizations as $contactProfileOrganization) {
            $this->registerContactProfileOrganization($contactProfileOrganization);
        }
    }

    public function createContactProfileOrganizationWithRandomOrg(ContactProfile $contactProfile, $round = 1): int
    {
        /**
         * Finding all bound orgs
         */
        $ownOrgBindings = ContactProfileOrganization::where(['contact_profile_id' => $contactProfile->id])->get();
        $ownOrgIds = [];
        foreach ($ownOrgBindings as $ownOrgBinding) {
            $ownOrgIds[] = $ownOrgBinding->organization_id;
        }

        /**
         * Finding a random org in the database
         */
        $randomOrg = Organization::whereNotIn('id', $ownOrgIds)->inRandomOrder()->first();
        if ($randomOrg) {
            $this->createContactProfileOrganization($randomOrg->id, $contactProfile);

            return 1;
        }

        // Ha nincs elérhető szervezet, visszatér egy hibaüzenettel vagy kivétellel
        // throw new \Exception('No available organization to link with this contact profile.');
        return 0;
    }

    public function createContactProfileOrganization(int $orgId, ?ContactProfile $contactProfile = null)
    {
        if ($contactProfile instanceof ContactProfile) {
            $contactProfileOrganization = ContactProfileOrganization::create([
                'contact_profile_id' => $contactProfile->id,
                'organization_id' => $orgId,
            ]);

            $this->registerContactProfileOrganization($contactProfileOrganization);

            return $contactProfileOrganization;
            // dump($contactProfileOrganization);exit;
        }
    }

    public function createContactProfileOrganizationKey($contact_profile_id, $organization_id)
    {
        return $contact_profile_id.'-'.$organization_id;
    }

    public function registerContactProfileOrganization(ContactProfileOrganization $contactProfileOrganization)
    {
        $key = $this->createContactProfileOrganizationKey($contactProfileOrganization->contact_profile_id, $contactProfileOrganization->organization_id);

        if (! $this->isRegisteredContactProfileOrganization($contactProfileOrganization->contact_profile_id, $contactProfileOrganization->organization_id)) {
            $this->registeredContactProfileOrganizations[$key] = true;
        }
    }

    public function isRegisteredContactProfileOrganization($contact_profile_id, $organization_id)
    {
        $key = $this->createContactProfileOrganizationKey($contact_profile_id, $organization_id);

        if (isset($this->registeredContactProfileOrganizations[$key]) && $this->registeredContactProfileOrganizations[$key]) {
            return false;
        }

        return true;
    }
}
