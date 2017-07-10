<?php

namespace LaravelSalesforce;

class Report extends EnterpriseClient
{
    protected $objName = 'Report';

    public function getReport($id, $includeDetails = true)
    {
        return $this->runReport(['id' => $id, 'includeDetails' => var_export($includeDetails, true)]);
    }
}
