<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Customer\Builders\OrgAddressBuilder;
use Domain\Customer\Observers\OrgAddressObserver;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Country;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Organization's Addresses
 *
 * @property \Domain\Shared\Enums\CorporateAddressType|null $type
 */
#[ObservedBy([OrgAddressObserver::class])]
final class OrgAddress extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'country_id',
        'title',
        'main',
        'address_type',
        'postal_code',
        'city',
        'street_name',
        'public_place_category',
        'number',
        'building',
        'floor',
        'door',
        'address',
        'lane',
        'region',
        'shipping_notes',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orgaddresses';

    public function newEloquentBuilder($query): OrgAddressBuilder
    {
        return new OrgAddressBuilder($query);
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization()->first() instanceof Organization ? $this->organization()->first() : null;
    }

    /**
     * Get the Organization who owns this address record
     *
     * @return BelongsTo<Organization, OrgAddress>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get the Country associated with this address
     *
     * @return BelongsTo<Country, OrgAddress>
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

    public function getLane(): ?string
    {
        if ($this->lane) {
            return $this->lane;
        }
        if ($this->address) {
            return $this->address;
        }

        $parts = [
            $this->street_name ?? '',
            $this->public_place_category ?? '',
            $this->number ?? '',
            $this->building ?? '',
            $this->floor ?? '',
            $this->door ?? '',
        ];

        return implode(' ', array_filter($parts));
    }

    /**
     * Interact with the address.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, string>
     */
    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => $this->composeAddress($value),
        );
    }

    /**
     * Interact with the number in address.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, string>
     */
    protected function number(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => mb_strlen(strval($value)) > 0 ? $value.(Str::endsWith($value, '.') ? '' : '.') : '',
        );
    }

    /**
     * Interact with the door in address.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, string>
     */
    protected function door(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => mb_strlen(strval($value)) > 0 ? $value.(Str::endsWith($value, '.') ? '' : '.') : '',
        );
    }

    protected function composeAddress(string $value): string
    {
        if (mb_strlen($value) > 3) {
            return $value;
        }
        $parts = [
            $this->street_name ?? '',
            $this->public_place_category ?? '',
            $this->number ?? '',
            $this->building ?? '',
            $this->floor ?? '',
            $this->door ?? '',
        ];

        return implode(' ', array_filter($parts));
    }
}
