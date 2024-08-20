<?php

namespace Domain\Customer\Builders;

use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserService;

class ContactProfileBuilder extends BaseBuilder
{
    /**
     * Admin can list all Customers, regardless of anything.
     */
    public function listableByAdmin()
    {
        return $this;
    }

    public function searchableByCustomer()
    {
        $contact = UserService::getContact();

        return $this
            ->valid()
            ->where('id', $contact ? $contact->getContactProfile()->id : null);
    }

    public function listableByCustomer()
    {
        return $this->searchableByCustomer();
    }

    public function validEmail()
    {
        return $this->whereNotNull('email');
    }

    public function valid()
    {
        return $this
            ->whereNotNull('email')
            ->whereNotNull('firstname')
            ->whereNotNull('lastname')
            ->whereNotNull('mobile');
    }
}
