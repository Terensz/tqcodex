<?php

namespace Domain\Communication\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;

class CommunicationDispatchBuilder extends BaseBuilder
{
    /**
     * Listables by the current customer:
     * - Has CommunicationDispatchProcess
     * > - CommunicationDispatchProcess is listable by the current Customer.
     * > - Can accept CommunicationDispatchProcess id, if it's not null, the subquery is filtered to this.
     */
    public function searchableByCustomer(?int $communicationDispatchProcessId = null)
    {
        return $this
            ->whereHas('communicationDispatchProcess', function ($subQuery) use ($communicationDispatchProcessId) {
                $subQuery->searchableByCustomer();

                if ($communicationDispatchProcessId) {
                    $subQuery->where(['id' => $communicationDispatchProcessId]);
                }
            });
    }

    public function listableByCustomer(?int $communicationDispatchProcessId = null)
    {
        return $this
            ->selectRaw('communicationdispatches.*')
            ->leftJoin('communicationdispatchprocesses', 'communicationdispatches.communicationdispatchprocess_id', '=', 'communicationdispatchprocesses.id')
            ->leftJoin('communicationcampaigns', 'communicationdispatchprocesses.communicationcampaign_id', '=', 'communicationcampaigns.id')
            ->groupBy('communicationdispatches.id')
            ->searchableByCustomer($communicationDispatchProcessId);
    }
}
