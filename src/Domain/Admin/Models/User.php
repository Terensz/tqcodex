<?php

declare(strict_types=1);

namespace Domain\Admin\Models;

use Database\Factories\Shared\UserFactory;
use Domain\Admin\Builders\UserBuilder;
use Domain\Communication\Mails\AdminEmailChangeRequestNotification;
use Domain\Communication\Mails\ResetAdminPasswordNotification;
use Domain\Customer\Models\Contact;
use Domain\Customer\Models\Organization;
use Domain\User\Events\EmailChangeNotificationMethodTriggered;
use Domain\User\Events\PasswordResetNotificationMethodTriggered;
use Domain\User\Models\Base\BaseUserModel;
use Domain\User\Models\Role;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $lastname
 * @property string $firstname
 * @property bool $is_admin
 */
final class User extends BaseUserModel
{
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    public const PROPERTY_FIRST_NAME = 'firstname';

    public const PROPERTY_LAST_NAME = 'lastname';

    public const PROPERTY_PHONE = 'phone';

    public const PROPERTY_EMAIL = 'email';

    public const PROPERTY_EMAIL_VERIFIED_AT = 'email_verified_at';

    public const PROPERTY_PASSWORD = 'password';

    public const PROPERTY_IS_ADMIN = 'is_admin';

    public const PROPERTY_PROFILE_IMAGE = 'photo_path';

    public const PROPERTY_REMEMBER_TOKEN = 'remember_token';

    public const PROPERTY_CREATED_AT = 'created_at';

    public const PROPERTY_UPDATED_AT = 'updated_at';

    public const PROPERTY_DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'firstname',
        'lastname',
        'password',
    ];

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    public function isDeletable(): bool
    {
        return true;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        /** @phpstan-ignore-next-line */
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function isAdmin(?User $user = null): bool
    {
        return true;
        // if ($user instanceof User) {
        //     return $user->is_admin;
        // }
        // return $this->is_admin;
    }

    /**
     * The Organizations that belong to the.
     *
     * @return BelongsToMany<Organization>
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user');
    }

    /**
     * The Contacts that belong to the.
     *
     * @return BelongsToMany<Contact>
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'contact_user');
    }

    /**
     * Get all of the IP addreses for the User.
     *
     * @return HasMany<Userip>
     */
    public function ips(): HasMany
    {
        return $this->hasMany(Userip::class, 'userip_id', 'user_id');
    }

    public function getNameAttribute(): string
    {
        return $this->lastname.' '.$this->firstname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function isDemoUser(): bool
    {
        return $this->email === 'johndoe@example.com';
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<User>  $query
     */
    public function scopeOrderByName(Builder $query): void
    {
        $query->orderBy('lastname')->orderBy('firstname');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<User>  $query
     * @param  array<string>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search): void {
            $query->where(function ($query) use ($search): void {
                $query->where('firstname', 'like', '%'.$search.'%')
                    ->orWhere('lastname', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role): void {
            $query->whereRole($role);
        });
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = route('admin.password.reset', ['token' => $token]);
        PasswordResetNotificationMethodTriggered::dispatch(UserService::ROLE_TYPE_ADMIN, $this, $url);
        $this->notify(new ResetAdminPasswordNotification($url));
    }

    public function sendEmailChangeRequestNotification($token, $originalEmail, $modifiedEmail): void
    {
        try {
            $url = route('admin.email-change.create', ['token' => $token]);
            EmailChangeNotificationMethodTriggered::dispatch(UserService::ROLE_TYPE_ADMIN, $this, $url);
            $this->email = $modifiedEmail;
            $this->save();
            $this->notify(new AdminEmailChangeRequestNotification($url));
            $this->email = $originalEmail;
            $this->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<User>
     */
    protected static function newFactory()
    {
        /** @phpstan-ignore-next-line */
        return UserFactory::new();
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

        UserRoleService::syncRolesToAdmin($roleNames, $this);

        return $saveResult;
    }

    protected function getStoredRole($role): Role
    {
        if ($role instanceof \BackedEnum) {
            $role = $role->value;
        }

        if (is_int($role) || PermissionRegistrar::isUid($role)) {
            return $this->getRoleClass()::findById($role, UserService::GUARD_ADMIN);
        }

        if (is_string($role)) {
            return $this->getRoleClass()::findByName($role, UserService::GUARD_ADMIN);
        }

        return $role;
    }

    // public function roles(): BelongsToMany
    // {
    //     $relation = $this->morphToMany(
    //         config('permission.models.role'), // $related (Domain\User\Models\Role)
    //         'model', // name
    //         config('permission.table_names.model_has_roles'), // $table (value: model_has_roles)
    //         config('permission.column_names.model_morph_key'), // $foreignPivotKey (value: model_id)
    //         app(PermissionRegistrar::class)->pivotRole // $relatedPivotKey (value: role_id)
    //     );

    //     if (! app(PermissionRegistrar::class)->teams) {
    //         return $relation;
    //     }

    //     $teamField = config('permission.table_names.roles').'.'.app(PermissionRegistrar::class)->teamsKey;

    //     return $relation->wherePivot(app(PermissionRegistrar::class)->teamsKey, getPermissionsTeamId())
    //         ->where(fn ($q) => $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId()));
    // }
}
