<?php

namespace Surge\LaravelSalesforce\Objects;

class Opportunity extends AbstractObject
{
    protected $objName = 'Opportunity';

    /**
     * Insert new account.
     *
     * @param $params
     *
     * @return bool
     */
    public function create($params)
    {
        $params['RecordTypeId'] = config('laravel-salesforce.oppurtunityrecordtypeid');
        $params['Divisions__c'] = $this->brandName;

        return $this->createRecord($this->objName, $params);
    }
}
