<?php

namespace Domain\Admin\Models;

use Domain\Shared\Models\BaseModel;

class UserActivityLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'useractivitylogs';

    protected $fillable = [
        'user_id',
        'action',
        'modified_property',
        'original_value',
        'modified_value',
        'ip_address',
        'host',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
