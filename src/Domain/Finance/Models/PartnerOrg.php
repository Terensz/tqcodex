<?php

declare(strict_types=1);

namespace Domain\Finance\Models;

use Domain\Customer\Models\Organization;
use Domain\Finance\Builders\PartnerOrgBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CompensationItem
 */
final class PartnerOrg extends Organization
{
    use SoftDeletes;

    protected $table = 'organizations';

    public function newEloquentBuilder($query): PartnerOrgBuilder
    {
        return new PartnerOrgBuilder($query);
    }

    /**
     * Get all compensation items where this organization is a partner.
     *
     * @return HasMany<CompensationItem>
     */
    public function compensationItems(): HasMany
    {
        return $this->hasMany(CompensationItem::class, 'partner_org_id', 'id');
    }
}
