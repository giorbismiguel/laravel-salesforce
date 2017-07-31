<?php

namespace Surge\LaravelSalesforce;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'salesforce';
    }
}
