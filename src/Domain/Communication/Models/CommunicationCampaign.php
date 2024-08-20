<?php

declare(strict_types=1);

namespace Domain\Communication\Models;

use Domain\Communication\Builders\CommunicationCampaignBuilder;
use Domain\Customer\Models\ContactOrg;
use Domain\Customer\Models\ContactProfile;
use Domain\Customer\Models\Organization;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Contact's Personal Emails
 */
final class CommunicationCampaign extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'reference_code',
        'title_lang_ref',
        'is_published',
        'is_listable',
        'is_editable',
        'is_redispatchable',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'communicationcampaigns';

    // public $timestamps = false;

    public function newEloquentBuilder($query): CommunicationCampaignBuilder
    {
        return new CommunicationCampaignBuilder($query);
    }

    /**
     * Get the organization that owns the campaign.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(ContactOrg::class, 'organization_id', 'id');
    }
    // public function organization(): BelongsTo
    // {
    //     return $this->belongsTo(Organization::class);
    // }

    /**
     * Get the dispatch processes for the campaign.
     */
    public function communicationDispatchProcesses(): HasMany
    {
        return $this->hasMany(CommunicationDispatchProcess::class, 'communicationcampaign_id');
    }

    /**
     * The contact profiles that belong to the campaign's organization.
     */
    // public function contactProfiles(): BelongsToMany
    // {
    //     return $this->belongsToMany(ContactProfile::class, 'contactprofile_organizations', 'organization_id', 'contact_profile_id');
    // }
}
