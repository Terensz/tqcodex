<?php

declare(strict_types=1);

namespace Domain\Communication\Models;

use Domain\Communication\Builders\CommunicationDispatchProcessBuilder;
use Domain\Customer\Models\Contact;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Contact's Personal Emails
 */
final class CommunicationDispatchProcess extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'communicationcampaign_id',
        'sender_contact_id',
        'communication_form',
        'status',
        'started_at',
        'finished_at',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'communicationdispatchprocesses';

    public $timestamps = false;

    public function newEloquentBuilder($query): CommunicationDispatchProcessBuilder
    {
        return new CommunicationDispatchProcessBuilder($query);
    }

    /**
     * Get the campaign that owns the dispatch process.
     */
    public function communicationCampaign(): BelongsTo
    {
        return $this->belongsTo(CommunicationCampaign::class, 'communicationcampaign_id');
    }

    /**
     * Get the sender contact that owns the dispatch process.
     */
    public function senderContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'sender_contact_id');
    }

    /**
     * Get the dispatches for the dispatch process.
     */
    public function communicationDispatches(): HasMany
    {
        return $this->hasMany(CommunicationDispatch::class, 'communicationdispatchprocess_id');
    }
}
