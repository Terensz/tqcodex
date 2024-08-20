<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Customer\Builders\ContactOrgBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CompensationItem
 */
final class ContactOrg extends Organization
{
    use SoftDeletes;

    public function newEloquentBuilder($query): ContactOrgBuilder
    {
        return new ContactOrgBuilder($query);
    }
}
