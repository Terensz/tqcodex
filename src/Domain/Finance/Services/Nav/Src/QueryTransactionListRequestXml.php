<?php

namespace Domain\Finance\Services\Nav\Src;

class QueryTransactionListRequestXml extends BaseRequestXml
{
    protected $rootName = 'QueryTransactionListRequest';

    /**
     * QueryTransactionListRequestXml constructor.
     */
    public function __construct($config, $insDate, $page)
    {
        parent::__construct($config);

        $this->xml->addChild('page', $page);

        XmlUtil::addChildArray($this->xml, 'insDate', $insDate);
    }
}
