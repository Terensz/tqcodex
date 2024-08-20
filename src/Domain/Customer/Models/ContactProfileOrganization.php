<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ContactProfileOrganization extends Model
{
    /**
     * Pivot table for contactprofiles and organizations.
     *
     * @var string
     */
    protected $table = 'contactprofile_organizations';

    protected $fillable = [
        'contact_profile_id',
        'organization_id',
    ];

    public $timestamps = false;

    /**
     * @return BelongsTo<ContactOrg, ContactProfileOrganization>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(ContactOrg::class, 'organization_id', 'id');
    }
}
