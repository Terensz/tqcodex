<?php

namespace Domain\Finance\Services\Nav\Src;

class QueryTaxpayerRequestXml extends BaseRequestXml
{
    protected $rootName = 'QueryTaxpayerRequest';

    public function __construct($config, $taxNumber)
    {
        parent::__construct($config);

        $this->xml->addChild('taxNumber', $taxNumber);
    }
}
