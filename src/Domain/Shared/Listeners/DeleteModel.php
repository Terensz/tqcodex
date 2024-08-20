<?php

namespace Domain\Shared\Listeners;

use Domain\Shared\Events\ModelDeleteRequested;

class DeleteModel
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ModelDeleteRequested $event): void
    {
        if ($event->model) {
            if (method_exists($event->model, 'isDeletable') && ! $event->model->isDeletable()) {
                return;
            }
            $deleteRes = $event->model->delete();
        }
    }
}

// $deleteRes = $event->model::destroy($event->model->id);
