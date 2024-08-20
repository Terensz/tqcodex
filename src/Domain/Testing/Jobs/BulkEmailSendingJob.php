<?php

namespace Domain\Testing\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BulkEmailSendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $data;

    public function __construct($data)
    {
        $this->onConnection('database');
        // Log::info('ProcessSaveBulkCompItem::__construct()');
        // log::info($this->data);
        $this->data = $data;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        // Log::info('ProcessSaveBulkComp job started.');
        // dump('handle@BulkEmailSendingJob');
        try {
            $data = $this->data;
            app(Pipeline::class)
                ->send($data)
                ->through([
                    \Domain\Testing\Pipes\SendTestEmails::class,
                ])
                ->via('handle')
                ->then(function ($data) {
                    Log::debug('Pipeline last action: finishPipe()');
                    $this->finishPipe($data);
                });

            // Log::info('ProcessSaveBulkComp job done.'); //exit;

        } catch (Exception $e) {
            throw $e;
            //Nem javasolt, hogy elszálljon egy kivétellel, hanem logolja és küldjön reportot.
            //ReportError::sendReport($this->data->uuid, 'Process Pipeline Exceptions: ', $e);
        }
    }

    public function finishPipe($data): void
    {
        //Javasolt a debug logba beküldeni valami azonosítót, pl.: $content->uuid
        // Log::debug('ProcessCSVUpload Pipeline successfuly finished: ');
    }
}
