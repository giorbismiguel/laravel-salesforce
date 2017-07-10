<?php

namespace Surge\LaravelSalesforce\Objects;

use Surge\LaravelSalesforce\Salesforce;

class Opportunity extends Salesforce
{
    protected $objName = 'Opportunity';

    /**
     * Insert new account.
     *
     * @param $params
     *
     * @return bool
     */
    public function insert($params)
    {
        $params['RecordTypeId'] = $this->opportunityRecord;
        $params['Divisions__c'] = $this->brandName;

        return $this->createRecord($this->objName, $params);
    }
}
