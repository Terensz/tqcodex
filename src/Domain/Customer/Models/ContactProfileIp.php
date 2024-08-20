<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ContactProfileIp extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactprofileips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_id',
        'ip',
        'action',
    ];

    /**
     * Get the Contact belongs to this ip record
     *
     * @return BelongsTo<ContactProfile, ContactProfileIp>
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(ContactProfile::class);
    }
}
