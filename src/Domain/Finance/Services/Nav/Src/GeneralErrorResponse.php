<?php

namespace Domain\Finance\Services\Nav\Src;

class GeneralErrorResponse extends BaseExceptionResponse
{
    public function getResult()
    {
        return (array) $this->xml->result;
    }
}
