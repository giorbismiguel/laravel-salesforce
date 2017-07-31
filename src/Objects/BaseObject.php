<?php

namespace Surge\LaravelSalesforce\Objects;

use Surge\LaravelSalesforce\Salesforce;

class BaseObject extends AbstractObject
{
    private $type;

    public function __construct(Salesforce $client, string $type = null)
    {
        $this->type = $type;

        parent::__construct($client);
    }
}