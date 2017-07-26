<?php

namespace Surge\LaravelSalesforce\Objects;

class BaseObject extends AbstractObject
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}