<?php

namespace LaravelSalesforce;

class Opportunity extends EnterpriseClient
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
