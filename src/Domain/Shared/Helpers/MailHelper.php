<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class MailHelper
{
    public $body;

    public $subject;

    public $sender;

    public $recipients = [];

    public function send()
    {
        if (empty($this->recipients)) {
            throw new InvalidArgumentException('Nincsenek megadva címzettek.');
        }

        if (empty($this->subject) || empty($this->body)) {
            throw new InvalidArgumentException('Hiányzó tárgy vagy tartalom.');
        }

        Mail::send([], [], function ($message) {
            $message->to($this->recipients)
                ->subject($this->subject)
                ->from($this->sender)
                ->setBody($this->body);
        });

        return true;
    }
}
