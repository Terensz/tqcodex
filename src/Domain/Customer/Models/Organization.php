<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Admin\Models\User;
use Domain\Customer\Builders\OrganizationBuilder;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $country_code
 */
class Organization extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_banned',
        'name',
        'long_name',
        'taxpayer_id',
        'vat_code',
        'county_code',
        'vat_verified_at',

        'org_type',
        'address_type',
        'company_email',
        'company_phone',

        'country_code',
        'county_code',
        'eutaxid',
        'taxid',
        'location',

        'social_media',
        'map_coordinates',
        'description',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organizations';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vat_banned' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // public function newEloquentBuilder($query): OrganizationBuilder
    // {
    //     return new OrganizationBuilder($query);
    // }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    /**
     * Gets the Organization's addresses
     *
     * @return HasMany<OrgAddress>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(OrgAddress::class, 'organization_id', 'id');
    }

    /**
     * The Contacts that belong to the Org.
     *
     * @return BelongsToMany<ContactProfile>
     */
    // public function contactProfiles(): BelongsToMany
    // {
    //     return $this->belongsToMany(ContactProfile::class, 'contactprofile_organizations');
    // }

    /**
     * The Contacts that belong to the Org.
     *
     * @return BelongsToMany<ContactProfile>
     */
    public function contactProfiles(): BelongsToMany
    {
        return $this->belongsToMany(ContactProfile::class, 'contactprofile_organizations', 'organization_id', 'contact_profile_id');
    }

    /**
     * The Users that belong to the Org.
     *
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the Country associated with the Organization.
     *
     * @return HasOne<Country>
     */
    public function country(): HasOne
    {
        return $this->hasOne(Country::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Organization>  $query
     * @param  array<string>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where(function ($query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('long_name', 'like', '%'.$search.'%')
                    ->orWhere('company_email', 'like', '%'.$search.'%')
                    ->orWhere('taxpayer_id', 'like', '%'.$search.'%')
                    ->orWhere('eutaxid', 'like', '%'.$search.'%')
                    ->orWhere('taxid', 'like', '%'.$search.'%');
            });
        });
    }

    /**
     * Find if the Organization is_banned (dead-file, etc.).
     *
     * @param  string  $data  (email | VAT/TAX ID)
     */
    public static function isBanned(string $data): bool
    {
        $org = Organization::where('company_email', $data)
            ->orWhere('taxpayer_id', $data)
            ->orWhere('eutaxid', $data)
            ->orWhere('taxid', $data)
            ->first();
        // if ($org && $org instanceof Organization) {
        //     return $org->is_banned;
        // }
        if ($org) {
            return $org->is_banned;
        }

        return false;
    }

    /**
     * Find if the Organization VAT ID is is_banned.
     *
     * @param  string  $data  (email | VAT/TAX ID)
     */
    public static function isVatBanned(string $data): bool
    {
        $org = Organization::where('company_email', $data)
            ->orWhere('taxpayer_id', $data)
            ->first();
        // if ($org && $org instanceof Organization) {
        //     return $org->vat_banned;
        // }
        if ($org) {
            return $org->vat_banned;
        }

        return false;
    }
}
