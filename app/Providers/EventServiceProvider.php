<?php

namespace App\Providers;

use Domain\Shared\Events\ModelDeleteRequested;
use Domain\Shared\Events\ModelModified;
use Domain\Shared\Events\ModelSaved;
use Domain\Shared\Events\RouteCalled;
use Domain\Shared\Listeners\CreateActivityLog;
use Domain\Shared\Listeners\CreateFailedAuthActivityLog;
use Domain\Shared\Listeners\CreateModelModificationActivityLog;
use Domain\Shared\Listeners\DeleteModel;
use Domain\Shared\Listeners\LogModelModifications;
use Domain\Shared\Listeners\SendResetPasswordNotification;
use Domain\System\Listeners\SaveVisitLog;
use Domain\User\Events\ActivityLogRequested;
use Domain\User\Events\EmailChangeNotificationMethodTriggered;
use Domain\User\Events\FailedAuth;
use Domain\User\Events\PasswordResetNotificationMethodTriggered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        ModelSaved::class => [
            /**
             * Handles the logging of an entire model
             */
            LogModelModifications::class,
        ],
        ModelModified::class => [
            /**
             * Creates a single log entry
             */
            CreateModelModificationActivityLog::class,
        ],
        ActivityLogRequested::class => [
            /**
             * Generally logs user activity.
             * - Logs model changes
             * - Logs page visits
             */
            CreateActivityLog::class,
        ],
        RouteCalled::class => [
            SaveVisitLog::class,
        ],
        FailedAuth::class => [
            CreateFailedAuthActivityLog::class,
        ],
        ModelDeleteRequested::class => [
            DeleteModel::class,
        ],
        PasswordResetNotificationMethodTriggered::class => [
            // SendResetPasswordNotification::class,
        ],
        EmailChangeNotificationMethodTriggered::class => [
            // SendEmailChangedNotification::class,
        ],
        PasswordReset::class => [
            //
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void {}

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
