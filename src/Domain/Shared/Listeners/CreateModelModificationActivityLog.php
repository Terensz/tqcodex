<?php

namespace Domain\Shared\Listeners;

use Domain\Shared\Events\ModelModified;
use Domain\User\Events\ActivityLogRequested;

class CreateModelModificationActivityLog
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ModelModified $event): void
    {
        ActivityLogRequested::dispatch(
            $event->roleType,
            $event->userModel,
            ($event->new ? CreateActivityLog::ACTION_MODEL_CREATION : CreateActivityLog::ACTION_MODEL_MODIFICATION),
            $event->change[LogModelModifications::KEY_PROPERTY],
            $event->change[LogModelModifications::KEY_ORIGINAL_VALUE],
            $event->change[LogModelModifications::KEY_MODIFIED_VALUE]
        );
    }
}
