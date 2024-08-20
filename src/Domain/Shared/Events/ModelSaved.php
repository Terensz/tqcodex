<?php

namespace Domain\Shared\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelSaved
{
    use Dispatchable, SerializesModels;

    public $model;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
