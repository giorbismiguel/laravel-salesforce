<?php

namespace Surge\LaravelSalesforce\Objects;

use \Surge\LaravelSalesforce\Salesforce;

class OpportunityLineItem extends Salesforce
{
    protected $objName = 'OpportunityLineItem';

    /**
     * @param $opportunityId
     */
    public function getProductByOpportunityId($opportunityId)
    {
        $query = 'Select Id, Name, Series__c, TotalPrice, Years__c,ProductCode, Quantity, PricebookEntryId From ' . $this->objName . ' Where OpportunityId = \'' . $opportunityId . '\' And IsDeleted = false';

        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return $response->records[0];
        }

        return false;
    }
}
