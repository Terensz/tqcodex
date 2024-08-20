<?php

namespace Domain\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FailedAuth
{
    use Dispatchable, SerializesModels;

    public $failedPassword;

    /**
     * Create a new event instance.
     */
    public function __construct($failedPassword)
    {
        $this->failedPassword = $failedPassword;
    }
}
