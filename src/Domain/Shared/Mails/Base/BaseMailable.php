<?php

namespace Domain\Shared\Mails\Base;

use Illuminate\Mail\Mailable;

class BaseMailable extends Mailable
{
    /**
     * NEVER, NEVER add a $to property to a Mailable!!!
     * That's used by the framework for special purposes.
     */
    // public $to;

    public $senderAddress;

    public $senderName;

    public $recipient = []; // [email, name]

    public $subject;

    public $body;
}
