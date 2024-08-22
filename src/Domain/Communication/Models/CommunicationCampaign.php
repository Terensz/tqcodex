<?php

declare(strict_types=1);

namespace Domain\Communication\Models;

use Domain\Communication\Builders\CommunicationCampaignBuilder;
use Domain\Shared\Models\BaseModel;
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
     * Get the dispatch processes for the campaign.
     */
    public function communicationDispatchProcesses(): HasMany
    {
        return $this->hasMany(CommunicationDispatchProcess::class, 'communicationcampaign_id');
    }
}
