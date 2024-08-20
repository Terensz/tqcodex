<?php

namespace Domain\Shared\Listeners;

use Domain\Shared\Events\ModelModified;
use Domain\Shared\Events\ModelSaved;
use Domain\User\Services\UserService;

class LogModelModifications
{
    public const KEY_PROPERTY = 'property';

    public const KEY_ORIGINAL_VALUE = 'originalValue';

    public const KEY_MODIFIED_VALUE = 'modifiedValue';

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(ModelSaved $event): void
    {
        /**
         * Getting all fillable data from the original model.
         */
        $model = $event->model;

        // if (!$model->id) {
        //     ModelCreateLogRequested::dispatch($roleType, $userModel, $change);
        // }

        $originalModelFillableData = [];
        $originalData = $model->getOriginal();
        /**
         * The only way to determine whether this is a new entity or a modification is: $model->getOriginal() is empty or not?
         */
        if (! empty($originalData)) {
            /**
             * Modifying.
             */
            $new = false;
            $modelClass = $model::class;
            $originalModel = new $modelClass;
            $originalModel->fill($originalData);
            $modelChanges = $model->getChanges();
        } else {
            /**
             * Creating new entity.
             *
             * We do a trick, because we want each and every fillable property to be logged as a creation.
             * Cloning model and artifically creating the $modelChanges array will cause the 2 foreach loops to create a log on each and every loop.
             */
            $new = true;
            $originalModel = clone $model;
            $modelChanges = $originalModel->toArray();
        }

        $originalModelFillableData = $originalModel->toArray();

        /**
         * Getting the changes, adding the previously extracted original data.
         */
        $changes = [];
        foreach ($modelChanges as $changedProperty => $modifiedValue) {
            foreach ($originalModelFillableData as $fillableProperty => $originalValue) {
                if ($changedProperty === $fillableProperty) {
                    $changes[] = [
                        LogModelModifications::KEY_PROPERTY => $fillableProperty,
                        LogModelModifications::KEY_ORIGINAL_VALUE => $originalValue,
                        LogModelModifications::KEY_MODIFIED_VALUE => $modifiedValue,
                    ];
                }
            }
        }

        if (count($changes) > 0) {
            /**
             * Checking each and every guards if they authenticated a user.
             */
            foreach (UserService::getGuardedUsers() as $roleType => $userModel) {
                if ($userModel && in_array($roleType, [UserService::ROLE_TYPE_ADMIN, UserService::ROLE_TYPE_CUSTOMER])) {
                    foreach ($changes as $change) {
                        ModelModified::dispatch($roleType, $userModel, $new, $change);
                    }
                }
            }
        }
    }
}
