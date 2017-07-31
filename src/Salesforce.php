<?php

namespace Surge\LaravelSalesforce;

use Event;
use Surge\LaravelSalesforce\Objects\BaseObject;

class Salesforce
{
    /**
     * @var string
     */
    protected $objName;

    /**
     * @var SalesforceAuth
     */
    private $auth;

    /**
     * Salesforce constructor.
     *
     * @param                $client
     * @param SalesforceAuth $auth
     */
    public function __construct($client, SalesforceAuth $auth)
    {
        $this->client = $client;
        $this->auth = $auth;
    }

    /**
     * @param  stirng $method
     * @param  array  $args
     * @return bool|mixed|string
     */
    public function __call($method, $args)
    {
        if (0 === strpos($method, 'create')) {
            return $this->callCreateOnObject($method, $args);
        }

        if (0 === strpos($method, 'update')) {
            return $this->callUpdateOnObject($method, $args);
        }

        if (0 === strpos($method, 'delete')) {
            return $this->callDeleteOnObject($method, $args);
        }

        if (0 === strpos($method, 'get')) {
            return $this->callGetOnObject($method, $args);
        }

        return (new BaseObject(''))->{$method}($args);
    }

    /**
     * Create object dynamically
     *
     * @param $method
     * @param $args
     * @return bool
     */
    private function callCreateOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class())->create($args[0]);
        }

        return (new BaseObject($type))->create($args[0]);
    }

    private function callUpdateOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class())->update($args[0]);
        }

        return (new BaseObject($type))->update($type, $args[0]);
    }

    private function callDeleteOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class())->delete($args[0]);
        }

        return (new BaseObject($type))->delete($type, $args[0]);
    }

    private function callGetOnObject($method, $args)
    {
        $type = substr($method, 3);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class())->get($args[0]);
        }

        return (new BaseObject($type))->get($args[0]);
    }
}
