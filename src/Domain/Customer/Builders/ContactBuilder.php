<?php

namespace Domain\Customer\Builders;

use Domain\Customer\Enums\ContactStatus;
use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserService;

class ContactBuilder extends BaseBuilder
{
    /**
     * Admin can list all Customers, regardless of anything.
     */
    public function listableByAdmin()
    {
        return $this
            ->leftJoin('contactprofiles', 'contactprofiles.contact_id', '=', 'contacts.id');
    }

    public function searchableByCustomer()
    {
        $contact = UserService::getContact();

        return $this
            ->valid()
            ->where('id', $contact ? $contact->id : null);
    }

    public function listableByCustomer()
    {
        return $this->searchableByCustomer();
    }

    public function valid()
    {
        return $this
            ->verified()
            ->active()
            ->hasContactProfile();
    }

    public function verified()
    {
        return $this->whereNotNull('email_verified_at');
    }

    public function active()
    {
        return $this->where('status', ContactStatus::ACTIVE->value);
    }

    public function hasContactProfile()
    {
        return $this->whereHas('contactProfile');
    }

    public function registeredLastMonth()
    {
        return $this->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ]);
    }

    // public function orderByName()
    // {
    //     return $this->orderBy('lastname')->orderBy('firstname');
    // }

    // public function whereEmailIs($email)
    // {
    //     return $this->whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::EQUALS, $email);
    // }

    // public function whereContactProfilePropertyLike($propertyName, $value)
    // {
    //     return $this->valid()->whereHas('contactProfile', function ($query) use ($propertyName, $value) {
    //         $query->where($propertyName, 'like', '%'.$value.'%');
    //     });
    // }

    // public function orderByContactProfileProperty($property, $direction)
    // {
    //     return $this
    //         ->orderBy(
    //             $this->whereHas('contactProfile'),
    //             // ContactProfile::select($property)
    //             //     ->whereColumn('companies.user_id', 'users.id'),
    //             'asc'
    //         );
    // }
}
