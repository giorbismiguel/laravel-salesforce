<?php

namespace Surge\LaravelSalesforce;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'salesforce';
    }
}
