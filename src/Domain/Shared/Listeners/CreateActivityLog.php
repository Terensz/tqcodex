<?php

namespace Domain\Shared\Listeners;

use Domain\Admin\Models\UserActivityLog;
use Domain\Customer\Models\ContactActivityLog;
use Domain\User\Events\ActivityLogRequested;
use Domain\User\Services\UserService;

class CreateActivityLog
{
    public const ACTION_MODEL_CREATION = 'ModelCreation';

    public const ACTION_MODEL_MODIFICATION = 'ModelModification';

    public const ACTION_PAGE_VISIT = 'PageVisit';

    public const ACTION_AUTH_FAILED = 'AuthFailed';

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ActivityLogRequested $event): void
    {
        try {
            $propertyArray = [
                'action' => $event->action,
                'modified_property' => $event->modified_property,
                'original_value' => $event->original_value,
                'modified_value' => $event->modified_value,
                'ip_address' => $event->ip_address,
                'host' => $event->host,
                'user_agent' => $event->user_agent,
            ];
            if ($event->roleType === UserService::ROLE_TYPE_ADMIN) {
                $model = new UserActivityLog;
                $propertyArray['user_id'] = $event->user_id;
            } else {
                $model = new ContactActivityLog;
                $propertyArray['contact_id'] = $event->user_id;
            }
            $model->fill($propertyArray);
            $model->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
