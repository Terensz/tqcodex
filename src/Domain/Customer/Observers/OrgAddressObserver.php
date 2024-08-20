<?php

namespace Domain\Customer\Observers;

use Domain\Customer\Models\OrgAddress;

class OrgAddressObserver
{
    /**
     * Handle the OrgAddress "saving" event.
     *
     * @return void
     */
    public function saving(OrgAddress $orgAddress)
    {
        if ($orgAddress->main) {
            // Set all other addresses of the same organization to main = false
            OrgAddress::where('organization_id', $orgAddress->organization_id)
                ->where('id', '!=', $orgAddress->id)
                ->update(['main' => false]);
        }
    }
}
