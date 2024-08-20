<?php

namespace Domain\User\Models;

use AllowDynamicProperties;
use Domain\Shared\Events\ModelSaved;
use Domain\User\Builders\RoleBuilder;
use Domain\User\Services\PermissionService;
use Domain\User\Services\RolePermissionDataService;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role as BaseSpatieRoleModel;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

/**
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
#[AllowDynamicProperties]
class Role extends BaseSpatieRoleModel implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
        $this->table = config('permission.table_names.roles') ?: parent::getTable();
    }

    public function newEloquentBuilder($query): RoleBuilder
    {
        return new RoleBuilder($query);
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            ModelSaved::dispatch($model);
        });
    }

    /**
     * @return RoleContract|Role
     *
     * @throws RoleAlreadyExists
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = ['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']];
        if (app(PermissionRegistrar::class)->teams) {
            $teamsKey = app(PermissionRegistrar::class)->teamsKey;

            if (array_key_exists($teamsKey, $attributes)) {
                $params[$teamsKey] = $attributes[$teamsKey];
            } else {
                $attributes[$teamsKey] = getPermissionsTeamId();
            }
        }
        if (static::findByParam($params)) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        /** @phpstan-ignore-next-line */
        return Role::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            app(PermissionRegistrar::class)->pivotRole,
            app(PermissionRegistrar::class)->pivotPermission
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            config('permission.table_names.model_has_roles'),
            app(PermissionRegistrar::class)->pivotRole,
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a role by its name and guard name.
     *
     * @return RoleContract|Role
     *
     * @throws RoleDoesNotExist
     */
    public static function findByName(string $name, ?string $guardName = null): RoleContract
    {
        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (! $role) {
            throw RoleDoesNotExist::named($name, $guardName);
        }

        return $role;
    }

    /**
     * Find a role by its id (and optionally guardName).
     *
     * @return RoleContract|Role
     */
    public static function findById(int|string $id, ?string $guardName = null): RoleContract
    {
        // $guardName = $guardName ?? Guard::getDefaultName(static::class);

        // $role = static::findByParam([(new static())->getKeyName() => $id, 'guard_name' => $guardName]);
        $role = static::findByParam([(new Role)->getKeyName() => $id, 'guard_name' => $guardName]);

        if (! $role) {
            throw RoleDoesNotExist::withId($id, $guardName);
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @return RoleContract|Role
     */
    public static function findOrCreate(string $name, ?string $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (! $role) {
            return Role::valid()->create(['name' => $name, 'guard_name' => $guardName] + (app(PermissionRegistrar::class)->teams ? [app(PermissionRegistrar::class)->teamsKey => getPermissionsTeamId()] : []));
        }

        return $role;
    }

    /**
     * Finds a role based on an array of parameters.
     *
     * @return RoleContract|Role|null
     */
    protected static function findByParam(array $params = []): ?RoleContract
    {
        $query = Role::valid();

        if (app(PermissionRegistrar::class)->teams) {
            $teamsKey = app(PermissionRegistrar::class)->teamsKey;

            $query->where(fn ($q) => $q->whereNull($teamsKey)
                ->orWhere($teamsKey, $params[$teamsKey] ?? getPermissionsTeamId())
            );
            unset($params[$teamsKey]);
        }

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Determine if the role may perform the given permission.
     *
     * @param  string|int|Permission|\BackedEnum  $permission
     *
     * @throws PermissionDoesNotExist|GuardDoesNotMatch
     */
    public function hasPermissionTo($permission, ?string $guardName = null): bool
    {
        if ($this->getWildcardClass()) {
            return $this->hasWildcardPermission($permission, $guardName);
        }

        $permission = $this->filterPermission($permission, $guardName);

        if (! $this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $guardName ?? $this->getGuardNames());
        }

        if ($permission instanceof Permission) {
            return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
        }

        return false;
    }

    public function saveWithPermissionData(array $permissionData = [])
    {
        if (! $this->id) {
            $this->save();
            $this->refresh();
        }

        $this->permissions()->detach();

        foreach ($permissionData as $guard => $guardSuffixes) {
            foreach ($guardSuffixes as $suffix => $suffixParams) {
                foreach ([PermissionService::PREFIX_VIEW, PermissionService::PREFIX_CREATE, PermissionService::PREFIX_EDIT, PermissionService::PREFIX_DELETE] as $prefix) {
                    if ($permissionData[$guard][$suffix][$prefix]) {
                        $permissionName = PermissionService::createPermissionName($prefix, $suffix);
                        $foundPermission = Permission::where(['name' => $permissionName, 'guard_name' => $guard])->first();
                        if ($foundPermission && $foundPermission instanceof Permission) {
                            $this->permissions()->attach($foundPermission->id);
                        }
                    }
                }
            }
        }

        $saveResult = $this->save();

        UserRoleService::recreateSuperAdminRoleHasPermissions();

        return $saveResult;
    }

    public function save(array $options = [])
    {
        $parentSaveResult = parent::save($options);

        // $prefixes = [PermissionService::PREFIX_VIEW, PermissionService::PREFIX_CREATE, PermissionService::PREFIX_EDIT, PermissionService::PREFIX_DELETE];
        // $suffixes = ['User'.$this->name, 'Role'.$this->name];

        // foreach ($prefixes as $prefix) {
        //     foreach ($suffixes as $suffix) {
        //         $permissionName = PermissionService::createPermissionName($prefix, $suffix);
        //         $existingPermission = Permission::where('name', $permissionName)->first();

        //         if (! $existingPermission) {
        //             $newPermission = new Permission();
        //             $newPermission->name = $permissionName;
        //             $newPermission->guard_name = $this->guard_name; // Ez csak egy példa, a te esetedben ez lehet más
        //             $newPermission->save();
        //         }
        //     }
        // }

        // if ($this->original['name'] !== $this->attributes['name'] || $this->original['guard_name'] !== $this->attributes['guard_name']) {
        //     $this->removeRolePermissions($this->original['name'], $this->original['guard_name']);
        // }

        // Cache::forget('cache-builder');

        Cache::forget(RolePermissionDataService::CACHE_NAME);

        // $this->removeRolePermissions($this->original['name'], $this->original['guard_name']);

        // $this->createRolePermissions($this->attributes['name'], $this->attributes['guard_name']);

        $this->reassignRolePermissions($this->original['name'], $this->original['guard_name'], $this->attributes['name'], $this->attributes['guard_name']);

        /**
         * We should refresh SuperAdmin roles by this role, but not if this is the SuperAdmin role itself.
         */
        // if ($this->name !== RoleService::ROLE_SUPER_ADMIN) {
        //     UserRoleService::recreateSuperAdminRoleHasPermissions();
        // }

        return $parentSaveResult;
    }

    public function reassignRolePermissions($oldRoleName, $oldGuardName, $newRoleName, $newGuardName)
    {
        $oldRolePermissionNames = PermissionService::createRolePermissionNames($oldRoleName);

        // dump('== reassignRolePermissions ==');
        // dump($oldRoleName, $oldGuardName, $newRoleName, $newGuardName);
        // dump('== / reassignRolePermissions ==');

        foreach ($oldRolePermissionNames as $prefix => $oldRolePermissionName) {
            $newRolePermissionName = PermissionService::createRolePermissionNames($newRoleName, $prefix);
            $existingPermission = Permission::where(['name' => $oldRolePermissionName, 'guard_name' => $oldGuardName])->first();
            if ($existingPermission && $existingPermission instanceof Permission) {
                $existingPermission->name = $newRolePermissionName;
                $existingPermission->guard_name = $newGuardName;
                $existingPermission->save();
            } else {
                $newPermission = new Permission;
                $newPermission->name = $newRolePermissionName;
                $newPermission->guard_name = $newGuardName;
                $newPermission->save();
            }
        }
    }

    // public function removeRolePermissions($roleName, $guardName)
    // {
    //     $rolePermissionNames = PermissionService::createRolePermissionNames($roleName);

    //     foreach ($rolePermissionNames as $rolePermissionName) {
    //         $existingPermission = Permission::where(['name' => $rolePermissionName, 'guard_name' => $guardName])->first();
    //         if ($existingPermission) {
    //             // $existingPermission->delete();
    //         }
    //     }
    // }

    // public function createRolePermissions($roleName, $guardName)
    // {
    //     $rolePermissionNames = PermissionService::createRolePermissionNames($roleName);

    //     foreach ($rolePermissionNames as $rolePermissionName) {
    //         $existingPermission = Permission::where(['name' => $rolePermissionName, 'guard_name' => $guardName])->first();
    //         if (! $existingPermission) {
    //             $newPermission = new Permission();
    //             $newPermission->name = $rolePermissionName;
    //             $newPermission->guard_name = $guardName;
    //             $newPermission->save();
    //         }
    //     }
    // }

    // protected function queryCacheKey()
    // {
    //     return json_encode([$this->toSql() => $this->getBindings()]);
    // }
}
