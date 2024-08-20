<?php

namespace Domain\Finance\Services\Nav\Src;

class GeneralExceptionResponse extends BaseExceptionResponse
{
    public function getResult()
    {
        return (array) $this->xml;
    }
}
