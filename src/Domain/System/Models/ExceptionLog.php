<?php

namespace Domain\System\Models;

use Domain\Shared\Models\BaseModel;

/**
 * @property int $line
 */
class ExceptionLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exceptionlogs';

    protected $fillable = [
        'admin_id',
        'contact_id',
        'message',
        'code',
        'file',
        'line',
        'trace',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
