<?php

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class ContactActivityLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactactivitylogs';

    protected $fillable = [
        'contact_id',
        'action',
        'modified_property',
        'original_value',
        'modified_value',
        'ip_address',
        'host',
        'user_agent',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
