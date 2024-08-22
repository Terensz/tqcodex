<?php

namespace Domain\Admin\Models;

use Domain\Shared\Models\BaseModel;

class AdminActivityLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminactivitylogs';

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
        return $this->belongsTo(Admin::class);
    }
}
