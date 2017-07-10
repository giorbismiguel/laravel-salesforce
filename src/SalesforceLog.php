<?php

namespace LaravelSalesforce;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class SalesforceLog
{
    use InteractsWithSockets, SerializesModels;

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
