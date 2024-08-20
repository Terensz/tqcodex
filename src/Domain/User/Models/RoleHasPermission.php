<?php

declare(strict_types=1);

namespace Domain\User\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $role_id
 * @property int|null $permission_id
 */
final class RoleHasPermission extends BaseModel
{
    public $timestamps = false;

    // public $role_id;

    // public $permission_id;

    // public $testprop;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_has_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    /**
     * Get the Country associated with this address
     *
     * @return BelongsTo<Role, RoleHasPermission>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the Country associated with this address
     *
     * @return BelongsTo<Permission, RoleHasPermission>
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
