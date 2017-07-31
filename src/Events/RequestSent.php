<?php

namespace Surge\LaravelSalesforce\Events;

class RequestSent
{
    /**
     * @var array
     */
    public $log;

    /**
     * Create a new job instance.
     * $log
     *  - data
     *  - class
     *  - url
     *  - type.
     *
     * @param array $log
     */
    public function __construct($log)
    {
        $this->log = $log;
    }
}
