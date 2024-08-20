<?php

namespace Domain\Finance\Builders;

use Domain\Customer\Builders\OrganizationBuilder;

class RecorderOrgBuilder extends OrganizationBuilder
{
    public function applyCompensationItemJoin()
    {
        return $this->join('compensationitems', 'organizations.id', '=', 'compensationitems.partner_org_id');
    }

    public function isCompensationItemIssuer()
    {
        return $this
            ->applyCompensationItemJoin()
            ->whereHas('compensationItems', function ($query) {
                $query->whereNotNull('organization_id');
            });
    }
}
