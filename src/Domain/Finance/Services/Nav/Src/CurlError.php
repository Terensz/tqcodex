<?php

namespace Domain\Finance\Services\Nav\Src;

use Exception;

class CurlError extends Exception
{
    protected $errno;

    public function __construct($errno)
    {
        $this->errno = $errno;

        $message = "Connection error. CURL error code: $errno";

        parent::__construct($message);
    }

    public function getErrno()
    {
        return $this->errno;
    }
}
