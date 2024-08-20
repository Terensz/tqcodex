<?php

namespace Domain\Customer\Builders;

class ContactOrgBuilder extends OrganizationBuilder
{
    public function hasAlma()
    {
        return $this->whereHas('contact');
    }
}
