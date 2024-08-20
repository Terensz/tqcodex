<?php

namespace Domain\Communication\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;

class CommunicationCampaignBuilder extends BaseBuilder
{
    public function searchableByCustomer()
    {
        return $this->where(function ($query) {
            $query
                ->noOrganization()
                ->orWhereHas('organization', function ($subQuery) {
                    $subQuery->searchableByCustomer();
                });
        });
    }

    public function listableByCustomer()
    {
        return $this
            ->searchableByCustomer()
            ->where(['is_listable' => true]);
    }

    public function noOrganization()
    {
        return $this->whereNull('organization_id');
    }

    public function hasOrganization()
    {
        return $this->whereHas('organization');
    }

    public function hasOrganizationListableByCustomer()
    {
        return $this->whereHas('organization', function ($query) {
            $query->listableByCustomer();
        });
    }
}
