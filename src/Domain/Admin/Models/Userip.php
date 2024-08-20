<?php

declare(strict_types=1);

namespace Domain\Admin\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Userip extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ip',
        'action',
    ];

    /**
     * Get the User belongs to this ip record
     *
     * @return BelongsTo<User, Userip>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
