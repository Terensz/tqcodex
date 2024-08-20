<?php

declare(strict_types=1);

namespace Domain\Shared\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ExceptionOccured extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->fromSender()),
            replyTo: [
                new Address($this->fromSender()),
            ],
            subject: 'Hiba történt: '.config('app.name', 'Elszámolási rendszer'),
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
            markdown: 'emails.exception',
            with: [
                'content' => $this->content,
            ],
        );
    }

    public function fromSender(): string
    {
        $fromSender = config('settings.email.webmaster.email', '');
        $fromSenderName = config('settings.email.webmaster.name', '');
        if (! $fromSender) {
            $fromSender = config('settings.email.default.email');
            $fromSenderName = config('settings.email.default.name');
        }

        return $fromSender.', '.$fromSenderName;
    }
}
