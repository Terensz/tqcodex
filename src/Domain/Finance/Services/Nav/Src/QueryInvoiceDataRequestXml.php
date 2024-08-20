<?php

namespace Domain\Finance\Services\Nav\Src;

class QueryInvoiceDataRequestXml extends BaseRequestXml
{
    protected $rootName = 'QueryInvoiceDataRequest';

    /**
     * QueryInvoiceDataRequestXml constructor.
     *
     * @throws \Exception
     */
    public function __construct($config, $invoiceNumberQuery)
    {
        parent::__construct($config);

        XmlUtil::addChildArray($this->xml, 'invoiceNumberQuery', $invoiceNumberQuery);
    }
}
