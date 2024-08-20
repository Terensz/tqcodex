<?php

declare(strict_types=1);

namespace Domain\Communication\Models;

use Domain\Communication\Builders\CommunicationDispatchBuilder;
use Domain\Customer\Models\Contact;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Contact's Personal Emails
 */
final class CommunicationDispatch extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'communicationdispatchprocess_id',
        'sender_address',
        'sender_name',
        'recipient_contact_id',
        'recipient_address',
        'recipient_name',
        'subject',
        'body',
        'bounced_message',
        'sent_at',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'communicationdispatches';

    public $timestamps = false;

    public function newEloquentBuilder($query): CommunicationDispatchBuilder
    {
        return new CommunicationDispatchBuilder($query);
    }

    /**
     * Get the dispatch process that owns the dispatch.
     */
    public function communicationDispatchProcess(): BelongsTo
    {
        return $this->belongsTo(CommunicationDispatchProcess::class, 'communicationdispatchprocess_id');
    }

    /**
     * Get the contact that owns the dispatch.
     */
    public function recipientContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
