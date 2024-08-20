<?php

declare(strict_types=1);

use Domain\Customer\Models\Contact;
use Domain\Testing\Jobs\BulkEmailSendingJob;
use Domain\Testing\Mails\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

// use Illuminate\Support\Facades\Notification;

/*
Standalone run:
php artisan test tests/Feature/Communication/MailSendingTest.php
*/

beforeEach(function () {
    /** @phpstan-ignore-next-line  */
    $this->data = [
        'inviteEmailSubject' => 'Teszt e-mail',
        'inviteEmailBody' => 'Hello!',
        'senderName' => 'Admin',
        'senderAddress' => 'admin@almail.com',
        'current_contact' => Contact::factory()->createUntilNotTaken(),
        'dispatches' => [
            [
                // 'recipientName' => 'Kis János',
                'organizationName' => 'Kis Kft.',
                'partner_name' => 'Kis Kft.',
                'partner_contact' => 'Kis János',
                'partner_email' => 'kissjano@almail.com',
            ],
        ],
    ];
});

test('Mail sending test - Simple mail sending with the CommunicationDispatcher.', function () {
    Mail::fake();

    // \Domain\Testing\Jobs\BulkEmailSendingJob::dispatch($data);
    /** @phpstan-ignore-next-line  */
    \Domain\Testing\Services\BulkMailSender::dispatch($this->data);

    Mail::assertSent(TestMail::class);
});

test('Mail sending test - Mail sending via a Job.', function () {
    Mail::fake();
    Queue::fake();

    /** @phpstan-ignore-next-line  */
    BulkEmailSendingJob::dispatch($this->data);
    Queue::assertPushed(BulkEmailSendingJob::class, 1);

    /** @phpstan-ignore-next-line  */
    (new BulkEmailSendingJob($this->data))->handle();

    Mail::assertSent(TestMail::class);
});
