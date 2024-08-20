<?php

namespace Domain\Finance\Services\Nav\Src;

use Exception;

class QueryInvoiceDigestRequestXml extends BaseRequestXml
{
    protected $rootName = 'QueryInvoiceDigestRequest';

    /**
     * QueryInvoiceDigestRequestXml constructor.
     *
     * @throws Exception
     */
    public function __construct($config, $invoiceQueryParams, $page, $direction)
    {
        parent::__construct($config);

        $this->xml->addChild('page', $page);
        $this->xml->addChild('invoiceDirection', $direction);

        XmlUtil::addChildArray($this->xml, 'invoiceQueryParams', $invoiceQueryParams);
    }
}
