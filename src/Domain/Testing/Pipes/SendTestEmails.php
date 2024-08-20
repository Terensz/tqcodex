<?php

namespace Domain\Testing\Pipes;

use Closure;
use Domain\Testing\Services\BulkMailSender;

// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;

// use Illuminate\Mail\SentMessage;

class SendTestEmails
{
    public function handle($data, Closure $next)
    {
        BulkMailSender::dispatch($data);

        return $next($data);
    }
}
