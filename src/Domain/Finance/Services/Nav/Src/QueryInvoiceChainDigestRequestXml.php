<?php

namespace Domain\Finance\Services\Nav\Src;

class QueryInvoiceChainDigestRequestXml extends BaseRequestXml
{
    protected $rootName = 'QueryInvoiceChainDigestRequest';

    /**
     * QueryInvoiceChainDigestRequestXml constructor.
     */
    public function __construct($config, $invoiceChainQuery, $page)
    {
        parent::__construct($config);

        $this->xml->addChild('page', $page);

        XmlUtil::addChildArray($this->xml, 'invoiceChainQuery', $invoiceChainQuery);
    }
}
