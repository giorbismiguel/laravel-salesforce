<?php

namespace Surge\LaravelSalesforce\Objects;

class BaseObject extends AbstractObject
{
    private $type;

    public function __construct(string $type = null)
    {
        $this->type = $type;
    }
}