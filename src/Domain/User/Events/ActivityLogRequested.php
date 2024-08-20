<?php

namespace Domain\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class ActivityLogRequested
{
    use Dispatchable, SerializesModels;

    // const ACTION_MODEL_CREATION = 'ModelCreation';

    // const ACTION_MODEL_MODIFICATION = 'ModelModification';

    public $roleType;

    public $user_id;

    public $action;

    public $modified_property;

    public $original_value;

    public $modified_value;

    public $ip_address;

    public $host;

    public $user_agent;

    /**
     * Create a new event instance.
     */
    public function __construct(string $roleType, $user, string $action, ?string $modified_property = null, ?string $original_value = null, ?string $modified_value = null, ?string $ip_address = null, ?string $host = null, ?string $user_agent = null)
    {
        $this->roleType = $roleType;
        $this->user_id = $user ? $user->id : null;
        $this->action = $action;
        $this->modified_property = $modified_property;
        $this->original_value = $original_value;
        $this->modified_value = $modified_value;
        $this->ip_address = $ip_address ?: Request::ip();
        $this->host = $host ?: Request::host();
        $this->user_agent = $user_agent ?: Request::header('User-Agent');
    }
}
