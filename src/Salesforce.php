<?php

namespace Surge\LaravelSalesforce;

use GuzzleHttp\ClientInterface;
use Surge\LaravelSalesforce\Objects\BaseObject;

class Salesforce
{
    /**
     * @var string
     */
    protected $objName;

    public $baseUrl;

    public $instanceUrl;

    public $client;

    /**
     * Salesforce constructor.
     *
     * @param ClientInterface $client
     * @param string          $url
     * @param string          $instanceUrl
     */
    public function __construct(ClientInterface $client, string $url, string $instanceUrl)
    {
        $this->client = $client;
        $this->baseUrl = $url;
        $this->instanceUrl = $instanceUrl;
    }

    /**
     * @param  string $method
     * @param  array  $args
     * @return bool|mixed|string
     */
    public function __call($method, $args)
    {
        if (starts_with($method, 'create')) {
            return $this->callCreateOnObject($method, $args);
        }

        if (starts_with($method, 'update')) {
            return $this->callUpdateOnObject($method, $args);
        }

        if (starts_with($method, 'delete')) {
            return $this->callDeleteOnObject($method, $args);
        }

        if (starts_with($method, 'get')) {
            return $this->callGetOnObject($method, $args);
        }

        if (starts_with($method, 'exists')) {
            return $this->callExistsOnObject($method, $args);
        }

        $class = new BaseObject($this);

        return call_user_func_array([$class, $method], $args);
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
            return (new $class($this))->create($args[0]);
        }

        return (new BaseObject($this, $type))->create($args[0]);
    }

    private function callUpdateOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class($this))->update($args[0], $args[1]);
        }

        return (new BaseObject($this, $type))->update($type, $args[0]);
    }

    private function callDeleteOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class($this))->delete($args[0]);
        }

        return (new BaseObject($this, $type))->delete($args[0]);
    }

    private function callGetOnObject($method, $args)
    {
        $type = substr($method, 3);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            return (new $class($this))->get($args[0]);
        }

        return (new BaseObject($this, $type))->get($args[0]);
    }

    private function callExistsOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\' . $type;

        if (class_exists($class)) {
            if (isset($args[1])) { //second param is optional
                return (new $class($this))->exists($args[0], $args[1]);
            }

            return (new $class($this))->exists($args[0]);
        }

        return (new BaseObject($this, $type))->get($args[0]);
    }

    /**
     * Run query
     *
     * @param $query
     * @return mixed
     */
    public function runQuery($query)
    {
        $response = $this->query($query);

        if ($response && $response->totalSize > 0) {
            return $response->records;
        }

        return false;
    }

    /**
     * Run report.
     *
     * @param string $id
     * @param bool   $includeDetails
     * @return mixed
     *
     */
    public function getReport(string $id, bool $includeDetails = true)
    {
        return $this->report($id, $includeDetails);
    }
}
