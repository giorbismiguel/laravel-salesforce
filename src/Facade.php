<?php

namespace Surge\LaravelSalesforce;

class Facade
{
    protected static function getFacadeAccessor()
    {
        return 'salesforce';
    }
}
