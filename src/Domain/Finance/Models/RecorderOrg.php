<?php

declare(strict_types=1);

namespace Domain\Finance\Models;

use Domain\Customer\Models\Organization;
use Domain\Finance\Builders\RecorderOrgBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class RecorderOrg extends Organization
{
    use SoftDeletes;

    public function newEloquentBuilder($query): RecorderOrgBuilder
    {
        return new RecorderOrgBuilder($query);
    }

    /**
     * Get all compensation items where this organization is a partner.
     *
     * @return HasMany<CompensationItem>
     */
    public function compensationItems(): HasMany
    {
        return $this->hasMany(CompensationItem::class, 'organization_id', 'id');
    }
}
