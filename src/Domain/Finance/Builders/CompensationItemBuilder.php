<?php

namespace Domain\Finance\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;

class CompensationItemBuilder extends BaseBuilder
{
    public function hasContact()
    {
        return $this->whereHas('contact');
    }

    public function hasRecorderOrg()
    {
        return $this->whereHas('recorderOrg');
    }

    public function hasPartnerOrg()
    {
        return $this->whereHas('partnerOrg');
    }

    public function searchableByCustomer()
    {
        return $this
            ->whereHas('contact', function ($query) {
                $query->searchableByCustomer();
            });
    }

    public function listableByCustomer()
    {
        return $this
            ->selectRaw('compensationitems.*')
            ->leftJoin('contacts', 'compensationitems.contact_id', '=', 'contacts.id')
            ->leftJoin('contactprofiles', 'contactprofiles.contact_id', '=', 'contacts.id')
            ->leftJoin('organizations as creatororgs', 'compensationitems.organization_id', '=', 'creatororgs.id')
            ->leftJoin('organizations as partnerorgs', 'compensationitems.partner_org_id', '=', 'partnerorgs.id')
            ->searchableByCustomer();
    }

    public function partnerOrgId($partnerOrgId)
    {
        return $this
            ->whereHas('partnerOrg', function ($query) use ($partnerOrgId) {
                $query
                    ->where('organizations.id', $partnerOrgId);
            });
    }
}
