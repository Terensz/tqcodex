<?php

declare(strict_types=1);

namespace Domain\Shared\Models;

use Domain\Customer\Models\ContactProfileAddress;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $iso2
 * @property string $name
 */
final class Country extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iso2',
        'name',
        'phonecode',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the contact adresses that related to this Country.
     *
     * @return HasMany<ContactProfileAddress>
     */
    public function contactProfileAddresses(): HasMany
    {
        return $this->hasMany(ContactProfileAddress::class);
    }

    public function label(): string
    {
        return __('country.'.$this->iso2);
    }

    /**
     * Find ISO2 country code by ID.
     */
    public static function getIso2Code(int $id): ?string
    {
        $country = Country::find($id);
        if ($country instanceof Country) {
            return $country->iso2;
        }

        return null;
    }

    /**
     * Find country code ID by ISO2.
     */
    public static function getIdByCode(string $code): ?int
    {
        $country = Country::where('iso2', $code)->first();
        if ($country instanceof Country) {
            return $country->id;
        }

        return null;
    }

    /**
     * Find country name by ISO2.
     */
    public static function getNameByCode(string $code): ?string
    {
        $country = Country::where('iso2', $code)->first();
        if ($country instanceof Country) {
            return $country->name;
        }

        return null;
    }

    /**
     * Find country name by ID.
     */
    public static function getNameById(int $id): ?string
    {
        $country = Country::find($id);
        if ($country instanceof Country) {
            return $country->name;
        }

        return null;
    }
}
