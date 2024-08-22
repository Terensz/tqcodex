<?php

namespace Domain\System\Models;

use Domain\Shared\Models\BaseModel;
use Domain\System\Builders\VisitLogBuilder;

class VisitLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visitlogs';

    protected $fillable = [
        'admin_id',
        'contact_id',
        'url',
        // 'route_name',
        'ip_address',
        'host',
        'user_agent',
        'count_of_visits',
        'day',
    ];

    public function newEloquentBuilder($query): VisitLogBuilder
    {
        return new VisitLogBuilder($query);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
