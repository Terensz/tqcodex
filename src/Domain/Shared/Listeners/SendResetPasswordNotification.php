<?php

namespace Domain\Shared\Listeners;

use Domain\User\Events\PasswordResetNotificationMethodTriggered;
use Illuminate\Notifications\Notifiable;

class SendResetPasswordNotification
{
    use Notifiable;

    public $email;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     *
     * ! Warning! This would work, but totally spoils the test.
     */
    public function handle(PasswordResetNotificationMethodTriggered $event): void
    {
        // $this->email = $event->model->email;

        // if ($event->roleType === UserService::ROLE_TYPE_ADMIN) {
        //     $this->notify(new AdminResetPasswordNotification($event->resetPasswordUrl));
        // }
        // if ($event->roleType === UserService::ROLE_TYPE_CUSTOMER) {
        //     $this->notify(new CustomerResetPasswordNotification($event->resetPasswordUrl));
        // }
    }
}
