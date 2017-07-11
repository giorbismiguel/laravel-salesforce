<?php

namespace Surge\LaravelSalesforce\Objects;

use Surge\LaravelSalesforce\Salesforce;

class Opportunity extends Salesforce
{
    protected $objName = 'Opportunity';

    public function __construct()
    {
        $this->recordType = config('sf.oppurtunityrecordtypeid');
    }

    /**
     * Insert new account.
     *
     * @param $params
     *
     * @return bool
     */
    public function insert($params)
    {
        $params['RecordTypeId'] = $this->recordType;
        $params['Divisions__c'] = $this->brandName;



        return $this->createRecord($this->objName, $params);
    }
}
