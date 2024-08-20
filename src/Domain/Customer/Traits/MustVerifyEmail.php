<?php

namespace Domain\Customer\Traits;

use Domain\Customer\Models\Contact;
use Illuminate\Auth\Notifications\VerifyEmail;

trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! $this->getContact() && $this->getContact() instanceof Contact ? is_null($this->getContact()->email_verified_at) : false;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        if ($this->getContact() && $this->getContact() instanceof Contact) {
            return $this->getContact()->forceFill([
                'email_verified_at' => $this->freshTimestamp(),
            ])->save();
        }
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
}
