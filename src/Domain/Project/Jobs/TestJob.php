<?php

namespace Domain\Project\Jobs;

use Amp\Pipeline\Pipeline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $data;

    // public function __construct()
    // {
    //     $this->onConnection('database');
    //     Log::info('TestJob->__construct()');
    //     // log::info($this->data);
    //     $this->data = [
    //         'alma' => 'Alma!!!'
    //     ];
    // }

    /**
     * Execute the job
     */
    public function handle()
    {
        // Log::info('TestJob->handle()'); //exit;

        // try {
        //     // $content = $this->data;
        //     app(Pipeline::class)
        //         ->send($content)
        //         ->throught([
        //             \Domain\Project\PipesSaveBulkComp\SaveDataToDb::class,
        //             \Domain\Project\PipesSaveBulkComp\SendInviteEmails::class,
        //         ])
        //         ->via('handle')
        //         ->then(function ($content) {
        //             Log::debug('ProcessCSVUpload pipeline finish.');
        //             $this->finishPipe($content);
        //         });

        // } catch (\Exception $e) {
        //     throw $e;
        // }
    }

    public function finishPipe($content): void {}
}

// class ProcessSaveBulkCompItem implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable;

//     public $data;

//     public function __construct($data)
//     {
//         Log::info('ProcessSaveBulkCompItem->__construct()');
//         $this->data = $data;
//     }

//     public function handle()
//     {
//         Log::info('ProcessSaveBulkCompItem->handle() started');

//         try {
//             // A folyamat logikája itt
//             Log::info('Processing data: ' . json_encode($this->data));

//             // Például email küldése
//             // \Mail::to($email)->send(new PartnerInviteMail($subject, $body));

//             Log::info('ProcessSaveBulkCompItem->handle() completed');

//         } catch (\Exception $e) {
//             Log::error('Error in ProcessSaveBulkCompItem->handle(): ' . $e->getMessage());
//             throw $e;
//         }
//     }
// }
