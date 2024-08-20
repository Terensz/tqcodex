<?php

namespace App\Exceptions;

use Domain\System\Models\ExceptionLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $this->logExceptionToDatabase($e);
        });
    }

    /**
     * Logs exception to database.
     *
     * @return void
     */
    // protected function logExceptionToDatabase_OLD(Throwable $exception)
    // {
    //     if (Schema::hasTable('exceptionlogs')) {
    //         $log = new ExceptionLog();
    //         $log->message = $exception->getMessage();
    //         $log->code = $exception->getCode();
    //         $log->file = $exception->getFile();
    //         $log->line = $exception->getLine();
    //         $log->trace = $exception->getTraceAsString();
    //         $log->save();
    //     }
    // }

    /**
     * Logs exception to database.
     *
     * @return void
     */
    protected function logExceptionToDatabase(Throwable $exception)
    {
        // dump($exception);exit;
        // Ellenőrizzük, hogy van-e aktív adatbázis kapcsolat
        if (! $exception instanceof \Error && $this->databaseConnectionExists()) {
            $log = new ExceptionLog;
            $log->message = $exception->getMessage();
            $log->code = $exception->getCode();
            $log->file = $exception->getFile();
            $log->line = $exception->getLine();
            $log->trace = $exception->getTraceAsString();
            $log->save();
        }
    }

    protected function databaseConnectionExists()
    {
        try {
            if (Schema::hasTable('exceptionlogs')) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
