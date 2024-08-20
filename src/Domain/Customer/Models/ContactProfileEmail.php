<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contact's Personal Emails
 */
final class ContactProfileEmail extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'contactprofile_id',
        'title',
        'email',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactprofileemails';

    /**
     * Get the Contact belongs to this personal email address
     *
     * @return BelongsTo<ContactProfile, ContactProfileEmail>
     */
    public function contactProfile(): BelongsTo
    {
        return $this->belongsTo(ContactProfile::class);
    }
}
