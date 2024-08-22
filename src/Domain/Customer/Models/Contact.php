<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Admin\Models\Admin;
use Domain\Customer\Builders\ContactBuilder;
use Domain\Shared\Models\BaseModel;
use Domain\User\Models\Role;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Carbon;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

/**
 * Contact class - basic model of the Customer
 */
final class Contact extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasRoles;
    // use MustVerifyEmail;

    // use Notifiable;
    use SoftDeletes;

    public const PROPERTY_EMAIL_VERIFIED_AT = 'email_verified_at';

    public const PROPERTY_PASSWORD = 'password';

    public const PROPERTY_REMEMBER_TOKEN = 'remember_token';

    public const PROPERTY_CREATED_AT = 'created_at';

    public const PROPERTY_UPDATED_AT = 'updated_at';

    public const PROPERTY_DELETED_AT = 'deleted_at';

    protected $guard = 'contact';

    // public $contactProfile;

    // public $technicalProperties = [
    //     'contactProfile',
    // ];

    // protected $setToNullOnDelete = [
    //     'email',
    //     'firstname',
    //     'middlename',
    //     'lastname',
    //     'phone',
    //     'mobile',
    //     'password',
    //     'profile_image',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'contactProfile',
        'referred_by',
        'is_banned',
        'level',
        'money_spent',
        'email_verified_at',
        'password',
        'segment',
        'type',
        'terms_ok',
        'news_ok',
        'direct_sales_ok',
        'photo_show_ok',
        'referral_key',
    ];

    // protected $appends = ['contactProfile'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'terms_ok' => 'boolean',
        'news_ok' => 'boolean',
        'direct_sales_ok' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // public function __construct()
    // {
    //     // $this->attributes['contactProfile'] = null;
    // }

    public function newEloquentBuilder($query): ContactBuilder
    {
        return new ContactBuilder($query);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        /** @phpstan-ignore-next-line */
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    /**
     * Get the Contact who was the referrer of this Contact. (Parent).
     */
    public function referrer(): ?Contact
    {
        /** @phpstan-ignore-next-line */
        return Contact::find($this->referred_by) ?? null;
    }

    /**
     * Gets the Contacts who where referred by this Contact. (Childrens).
     *
     * @return Collection<int, Contact>
     */
    public function referrals(): Collection
    {
        /** @phpstan-ignore-next-line */
        return Contact::where('referred_by', $this->id)->get();
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return false;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->getContactProfile()->sendEmailVerificationNotification();
    }

    public function contactProfile()
    {
        return $this->hasOne(ContactProfile::class);
    }

    // public function profile()
    // {
    //     return $this->hasOne(ContactProfile::class);
    // }

    public function getContactProfile(): ?ContactProfile
    {
        return $this->contactProfile()->first();
    }

    public function getNameAttribute(): ?string
    {
        return $this->getContactProfile() ? $this->getContactProfile()->getName() : null;
    }

    public function getEmail(): ?string
    {
        return $this->getContactProfile() ? $this->getContactProfile()->email : null;
    }

    public function getEmailForPasswordReset(): ?string
    {
        return $this->getEmail();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Contact>  $query
     * @param  array<string>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where(function ($query) use ($search): void {
                $query->where('firstname', 'like', '%'.$search.'%')
                    ->orWhere('lastname', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
                // ->orWhereHas('organizations', function ($query) use ($search): void {
                //     $query->where('name', 'like', '%'.$search.'%')
                //         ->orWhere('long_name', 'like', '%'.$search.'%')
                //         ->orWhere('company_email', 'like', '%'.$search.'%');
                // })
            });
        });
    }

    public function isNotBanned(?string $email = null)
    {
        if ($email) {
            $contact = Contact::where('email', $email)->first();
            if ($contact instanceof Contact) {
                return ! $contact->is_banned;
            }

            return true;
        }

        return ! $this->is_banned;
    }

    /**
     * Find if the Contact is_banned (dead-file, etc.).
     */
    public static function isBanned(string $email): bool
    {
        $contact = Contact::where('email', $email)->first();
        if ($contact instanceof Contact) {
            return $contact->is_banned;
        }

        return false;
    }

    public function sendPasswordResetNotification($token): void
    {
        $contactProfile = $this->getContactProfile();
        if (! $contactProfile) {
            throw new Exception('Invalid contact');
        }

        $contactProfile->sendPasswordResetNotification($token);
    }

    // public function sendEmailChangeRequestNotification($token, $originalEmail, $modifiedEmail): void
    // {
    //     $contactProfile = $this->getContactProfile();
    //     if (! $contactProfile) {
    //         throw new Exception('Invalid contact');
    //     }

    //     $contactProfile->sendEmailChangeRequestNotification($token, $originalEmail, $modifiedEmail);
    // }

    public function delete()
    {
        // foreach ($this->setToNullOnDelete as $property) {
        //     $this->$property = null;
        // }

        if ($this->contactProfile()->first()) {
            $this->contactProfile()->first()->delete();
        }

        $this->deleted_at = Carbon::now();
        $this->save();
    }

    public function saveWithRoleData($roleData)
    {
        if (! $this->id) {
            $this->save();
            $this->refresh();
        }

        $roleNames = [];
        foreach ($roleData as $roleName => $roleActive) {
            if ($roleActive) {
                $roleNames[] = $roleName;
            }
        }

        $saveResult = $this->save();
        // $this->refresh();

        UserRoleService::syncRolesToCustomer($roleNames, $this);

        return $saveResult;
    }

    // public function save(array $options = [])
    // {
    //     $technicalProperties = [];

    //     // Removing the technical properties until saving.
    //     foreach ($this->technicalProperties as $technicalProperty) {
    //         if (array_key_exists($technicalProperty, $this->attributes)) {
    //             $technicalProperties[$technicalProperty] = $this->attributes[$technicalProperty];
    //             unset($this->attributes[$technicalProperty]);
    //         }
    //     }

    //     // Saving.
    //     $result = parent::save($options);

    //     // Restoring the technical properties.
    //     foreach ($technicalProperties as $technicalProperty => $value) {
    //         $this->attributes[$technicalProperty] = $value;
    //     }

    //     return $result;
    // }

    // public function syncRoles(...$roles)
    // {
    //     if ($this->getModel()->exists) {
    //         $this->collectRoles($roles);
    //         $this->roles()->detach();
    //         $this->setRelation('roles', collect());
    //     }

    //     return $this->assignRole($roles);
    // }

    protected function getStoredRole($role): Role
    {
        if ($role instanceof \BackedEnum) {
            $role = $role->value;
        }

        if (is_int($role) || PermissionRegistrar::isUid($role)) {
            return $this->getRoleClass()::findById($role, UserService::GUARD_CUSTOMER);
        }

        if (is_string($role)) {
            return $this->getRoleClass()::findByName($role, UserService::GUARD_CUSTOMER);
        }

        return $role;
    }

    public function fillEmailVerifiedAtWithCurrentTime()
    {
        $this->email_verified_at = (string) now();
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    // public function getEmailForVerification()
    // {
    //     return $this->getContactProfile()->getEmailForVerification();
    // }
}
