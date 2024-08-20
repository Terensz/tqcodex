<?php

namespace Domain\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailChangeNotificationMethodTriggered
{
    use Dispatchable, SerializesModels;

    public $roleType;

    public $model;

    public $resetPasswordUrl;

    /**
     * Create a new event instance.
     */
    public function __construct(string $roleType, $model, string $resetPasswordUrl)
    {
        $this->roleType = $roleType;
        $this->model = $model;
        $this->resetPasswordUrl = $resetPasswordUrl;
    }
}
