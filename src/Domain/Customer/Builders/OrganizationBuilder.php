<?php

namespace Domain\Customer\Builders;

use Domain\Customer\Models\Organization;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserService;

class OrganizationBuilder extends BaseBuilder
{
    public function valid()
    {
        return $this;
    }

    // public function isPartner()
    // {
    //     return $this

    //         ;
    // }

    public function listableByAdmin()
    {
        return $this;
    }

    public function searchableByCustomer()
    {
        $ownContactProfile = UserService::getContactProfile();
        if (! $ownContactProfile) {
            throw new \Exception('No valid Customer session!');
        }

        return $this
            ->whereAssociatedPropertyIs('contactProfiles', 'id', BaseBuilder::EQUALS, $ownContactProfile->id);
    }

    public function listableByCustomer()
    {
        return $this->searchableByCustomer();
    }

    public function hasContactProfiles()
    {
        return $this->whereHas('contactProfiles');
    }

    public function doesntHaveContactProfiles()
    {
        return $this->whereDoesntHave('contactProfiles');
    }

    public function nameLike($name)
    {
        return $this->where('name', 'like', '%'.$name.'%');
    }

    // // Deprecated, use listableByCustomer() instead!!!
    // public static function getListQuery()
    // {
    //     $currentContactProfile = UserService::getContactProfile();

    //     if (! $currentContactProfile) {
    //         return null;
    //     }

    //     $query = Organization::query()
    //         ->join('contactprofile_organizations', 'organizations.id', '=', 'contactprofile_organizations.organization_id')
    //         ->leftJoin('orgaddresses as main_address', function ($join) {
    //             $join->on('organizations.id', '=', 'main_address.organization_id')
    //                 ->where('main_address.main', true);
    //         })
    //         ->where('contactprofile_organizations.contact_profile_id', $currentContactProfile->id)
    //         ->select(
    //             'organizations.id as id',
    //             'organizations.is_banned as is_banned',
    //             'organizations.name as name',
    //             'organizations.long_name as long_name',
    //             'organizations.location as location',
    //             'main_address.city as city',
    //         );

    //     return $query;
    // }

    // // Deprecated, use potentialPartner() instead!!!
    // public static function getValidPotentialPartnerOrg(int $id)
    // {
    //     $query = self::getPotentialPartnerOrgListQuery();
    //     $partnerOrg = $query ? $query->where('organizations.id', '=', $id)->first() : null;

    //     return $partnerOrg;
    // }

    // // Also deprecated. Use potentialPartner() instead!!!
    // public static function getPotentialPartnerOrgListQuery($searchedString = '')
    // {
    //     $query = Organization::query()
    //         ->join('contactprofile_organizations', 'organizations.id', '=', 'contactprofile_organizations.organization_id');

    //     $currentContactProfile = UserService::getContactProfile();

    //     if ($currentContactProfile) {
    //         $query
    //             ->whereNot('contactprofile_organizations.contact_profile_id', $currentContactProfile->id);
    //     }

    //     if (! empty($searchedString)) {
    //         $query->where('organizations.name', 'LIKE', '%'.$searchedString.'%');
    //     }

    //     return $query;
    // }
}
