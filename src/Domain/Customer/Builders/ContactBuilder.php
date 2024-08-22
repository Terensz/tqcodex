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
}
