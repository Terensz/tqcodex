<?php

namespace Domain\Testing\Mails;

use Domain\Shared\Helpers\StringHelper;
use Domain\Shared\Mails\Base\BaseMailable;
use Domain\Shared\Mails\Base\MailableInterface;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TestMail extends BaseMailable implements MailableInterface
{
    public const REFERENCE_CODE = 'PartnerInviteMail';

    public const TITLE_LANG_REF = 'project.PartnerInviteMailTitle';

    public $senderAddress;

    public $senderName;

    public function __construct($subject, $bodyMain, array $recipient, array $sender)
    {
        $bodyLayout = view('emails.project.layout.project-layout');
        $body = StringHelper::replacePlaceholders($bodyLayout, [
            'subject' => $subject,
            'body-main' => $bodyMain,
        ]);

        // $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->recipient = $recipient;

        if (count($sender) !== 2) {
            throw new \Exception('No contact logged in!');
        }

        $this->senderAddress = $sender[0];
        $this->senderName = $sender[1];
        $this->from[0] = [
            'address' => $this->senderAddress,
            'name' => $this->senderName,
        ];
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            // to: $this->to,
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            htmlString: $this->body,
        );
    }
}
