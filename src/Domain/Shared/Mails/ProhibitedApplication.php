<?php

declare(strict_types=1);

namespace Domain\Shared\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ProhibitedApplication extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Other data.
     *
     * @var array<string, mixed>
     */
    protected array $data;

    /**
     * Create a new message instance.
     *
     * @param  array<string, mixed>  $data
     * @return void
     */
    public function __construct(
        array $data
    ) {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Tiltott személy feliratkozási kísérlete',
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
            markdown: 'emails.prohibited',
            with: [
                'data' => $this->data,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<mixed>
     */
    public function attachments(): array
    {
        return [];
    }
}
