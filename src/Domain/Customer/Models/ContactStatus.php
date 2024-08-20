<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ContactStatus extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactstatuses';

    /**
     * Get the Order that owns the status.
     *
     * @return BelongsTo<Contact, ContactStatus>
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
