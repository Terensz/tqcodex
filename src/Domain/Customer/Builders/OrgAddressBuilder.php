<?php

namespace Domain\Customer\Builders;

use Domain\Customer\Models\OrgAddress;
use Domain\Customer\Models\Organization;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserService;

class OrgAddressBuilder extends BaseBuilder
{
    // // Deprecated method. Use listableByCustomer() instead!
    // public static function getListQuery()
    // {
    //     $currentContactProfile = UserService::getContactProfile();

    //     if (! $currentContactProfile) {
    //         return null;
    //     }

    //     // Ha van aktuális kapcsolatprofil, létrehozzuk az organizációk lekérdezését.
    //     $query = OrgAddress::query()
    //         ->join('organizations', 'organizations.id', '=', 'orgaddresses.organization_id')
    //         // Csatlakozunk a contactprofile_organizations táblához, amely kapcsolja az organizációkat a kapcsolatprofilokhoz.
    //         ->join('contactprofile_organizations', 'organizations.id', '=', 'contactprofile_organizations.organization_id')
    //         // Szűrjük a lekérdezést a jelenlegi kapcsolatprofil azonosítója alapján.
    //         ->where('contactprofile_organizations.contact_profile_id', $currentContactProfile->id)
    //         ->select(
    //             'orgaddresses.id as id',
    //             'organizations.name as organization_name',
    //             'orgaddresses.title as title',
    //             // 'compensationitems.invoice_amount as invoice_amount',
    //             // 'compensationitems.currency as currency',
    //             // 'compensationitems.invoice_id_for_compensation as invoice_id_for_compensation',
    //             // 'compensationitems.invoice_internal_id as invoice_internal_id',
    //             // 'po.name as partner_organization_name'
    //         );

    //     return $query;
    // }
}
