<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contact's Personal Addresses
 */
final class ContactProfileAddress extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_id',
        'type',
        'title',
        'main',
        'postal_code',
        'city',
        'lane',
        'region',
        'country_code',
        'country_id',
        'shipping_notes',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactprofileaddresses';

    /**
     * Get the Contact who has this address
     *
     * @return BelongsTo<ContactProfile, ContactProfileAddress>
     */
    public function contactProfile(): BelongsTo
    {
        return $this->belongsTo(ContactProfile::class, 'contact_profile_id');
    }

    /**
     * Get the Country associated with this address
     *
     * @return BelongsTo<Country, ContactProfileAddress>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getCountryName(): ?string
    {
        $country = Country::find($this->country_id);
        if ($country instanceof Country) {
            return $country->name;
        }

        return null;
    }
}
