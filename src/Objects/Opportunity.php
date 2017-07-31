<?php

namespace Surge\LaravelSalesforce\Objects;

class Opportunity extends AbstractObject
{
    /**
     * Insert new account.
     *
     * @param $params
     */
    public function create(array $params)
    {
        $params['RecordTypeId'] = config('laravel-salesforce.record_type.opportunity');
        $params['Divisions__c'] = config('laravel-salesforce.brand');

        return parent::create($params);
    }
}
