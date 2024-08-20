<?php

namespace Domain\Finance\Services\Nav\Src;

class QueryTransactionStatusRequestXml extends BaseRequestXml
{
    protected $rootName = 'QueryTransactionStatusRequest';

    public function __construct($config, $transactionId, $returnOriginalRequest = false)
    {
        parent::__construct($config);

        $this->xml->addChild('transactionId', $transactionId);

        if ($returnOriginalRequest) {
            $this->xml->addChild('returnOriginalRequest', $returnOriginalRequest);
        }
    }
}
