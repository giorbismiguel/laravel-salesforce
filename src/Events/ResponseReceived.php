<?php

namespace Surge\LaravelSalesforce\Events;

use Illuminate\Queue\SerializesModels;

class ResponseReceived
{
    use SerializesModels;

    /**
     * @var array
     */
    public $log;

    /**
     * Create a new job instance.
     * $log
     *  - options
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
