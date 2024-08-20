<?php

namespace Domain\Communication\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;

class CommunicationDispatchProcessBuilder extends BaseBuilder
{
    public function searchableByCustomer()
    {
        return $this->where(function ($query) {
            $query
                // CommunicationCampaign is associated is listable
                ->whereHas('communicationCampaign', function ($subQuery) {
                    $subQuery->searchableByCustomer();
                })
                // AND
                ->where(function ($subQuery) {
                    // has an associated sender Contact, which is listable by the current Contact.
                    $subQuery->whereHas('senderContact', function ($subSubQuery) {
                        $subSubQuery->listableByCustomer();
                    });
                });
        });
    }

    public function listableByCustomer()
    {
        return $this
            ->selectRaw('communicationdispatchprocesses.*, count(communicationdispatches.id) as count_dispatches')
            ->leftJoin('communicationdispatches', 'communicationdispatches.communicationdispatchprocess_id', '=', 'communicationdispatchprocesses.id')
            ->leftJoin('communicationcampaigns', 'communicationdispatchprocesses.communicationcampaign_id', '=', 'communicationcampaigns.id')
            ->groupBy('communicationdispatchprocesses.id')
            ->searchableByCustomer();
    }

    public function hasSenderContact()
    {
        return $this->whereHas('senderContact');
    }
}
