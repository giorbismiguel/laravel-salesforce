<?php

namespace Surge\LaravelSalesforce\Objects;

use \Surge\LaravelSalesforce\Salesforce;

class Payment extends Salesforce
{
    /**
     * @var string
     */
    protected $objName = 'Payment__c';

    /**
     * @param $opportunityId
     */
    public function getAllByOpportunityId($opportunityId)
    {
        $query = 'Select Id, Name, Opportunity__c, Net_amount__c, Payment_Date__c, Gross_Amount__c From ' . $this->objName . ' Where Opportunity__c = \'' . $opportunityId . '\' And IsDeleted = false';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return $response->records;
        }

        return false;
    }
}
