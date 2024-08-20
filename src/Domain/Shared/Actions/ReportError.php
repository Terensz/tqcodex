<?php

declare(strict_types=1);

namespace Domain\Shared\Actions;

use Domain\Shared\Mails\ExceptionOccured;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class ReportError
{
    /**
     * @param  array<string, mixed>  $data  (array of params)
     */
    public static function sendReport(array $data, string $message = '', ?Exception $e = null): void
    {
        // if an exception occurred
        Log::error($message.$e, [$data]);
        $emessage = $message;
        foreach ($data as $key => $value) {
            $emessage .= '<p>'.$key.' => '.$value.'</p>';
        }
        $emessage .= '<br>'.$e.'<br>';

        Mail::to(config('settings.email.webmaster'))->queue(new ExceptionOccured($emessage));
        //Standard Laravel Report
        if ($e) {
            report($e);
        }
    }
}
