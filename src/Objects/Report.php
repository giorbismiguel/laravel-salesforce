<?php

namespace Surge\LaravelSalesforce\Objects;

use Surge\LaravelSalesforce\Salesforce;

class Report extends Salesforce
{
    protected $objName = 'Report';

    public function getReport($id, $includeDetails = true)
    {
        return $this->runReport(['id' => $id, 'includeDetails' => var_export($includeDetails, true)]);
    }
}
