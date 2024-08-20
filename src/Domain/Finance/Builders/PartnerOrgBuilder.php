<?php

namespace Domain\Finance\Builders;

use Domain\Customer\Builders\OrganizationBuilder;
use Domain\User\Services\UserService;

class PartnerOrgBuilder extends OrganizationBuilder
{
    public function applyCompensationItemJoin()
    {
        return $this->join('compensationitems', 'organizations.id', '=', 'compensationitems.partner_org_id');
    }

    public function potentialPartner()
    {
        $ownContactProfile = UserService::getContactProfile();

        if (! $ownContactProfile) {
            return $this;
        }

        return $this->whereDoesntHave('contactProfiles', function ($query) use ($ownContactProfile) {
            $query->where('contact_profile_id', $ownContactProfile->id);
        });
    }

    public function hasCompensationItems()
    {
        return $this->whereHas('compensationItems');
    }

    public function isCompensationItemPartner()
    {
        return $this
            ->applyCompensationItemJoin()
            ->whereHas('compensationItems', function ($query) {
                $query->whereNotNull('partner_org_id');
            });
    }
}
