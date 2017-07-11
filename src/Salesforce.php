<?php

namespace Surge\LaravelSalesforce;

use Event;
use LaravelSalesforce\Exceptions\SalesforceException;

class Salesforce
{
    /**
     * @var mixed
     */
    protected $brandName;

    /**
     * @var string
     */
    protected $objName;

    /**
     * @var SalesforceAuth
     */
    private $auth;

    /**
     * @var array
     */
    private $version = [
        'label'   => 'Summer 17',
        'url'     => '/services/data/v39.0',
        'version' => '39.0',
    ];

    /**
     * Salesforce constructor.
     */
    public function __construct($client, SalesforceAuth $auth)
    {
        $this->brandName = config('sf.brand');

        $this->client = $client;
        $this->auth = $auth;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return mixed
     */
    private function sendRequest(string $method, string $url, array $options = [])
    {
        Event::fire(new RequestSent([
            'options' => $options,
            'url'     => $url,
            'class'   => get_class($this),
            'type'    => 'REQUEST',
        ]));

        $response = json_decode($this->client->request($method, $this->url.$url, $options)->getBody());

        Event::fire(new ResponseReceived([
            'options' => $response,
            'url'     => $url,
            'class'   => get_class($this),
            'type'    => 'RESPONSE',
        ]));

        if (!$response) {
            return;
        }

        return $response;
    }

    /**
     * Get latest version.
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->sendRequest('GET', $this->auth->instanceUrl.'/services/data');
    }

    /**
     * Get all organisation limits.
     */
    public function listOrganisationLimits()
    {
        return $this->sendRequest('GET', $this->auth->instanceUrl.$this->version['url'].'/limits');
    }

    /**
     * List all avaailable resources.
     *
     * @return mixed
     */
    public function listAvailableResources()
    {
        return $this->sendRequest('GET', '');
    }

    /**
     * List all objects.
     *
     * @return mixed
     */
    public function listObjects()
    {
        return $this->sendRequest('GET', '/sobjects', [], false);
    }

    /**
     * Describe an object.
     *
     * @param $objectName
     *
     * @return mixed
     */
    public function describeObject($objectName)
    {
        return $this->sendRequest('GET', '/sobjects/'.$objectName.'/describe', [], false);
    }

    /**
     * Describe basic object.
     *
     * @param $objectName
     *
     * @return mixed
     */
    public function describeBasicObject($objectName)
    {
        return $this->sendRequest('GET', '/sobjects/'.$objectName);
    }

    /**
     * Run Salesforce query.
     *
     * @param $query
     *
     * @return mixed
     */
    public function query($query)
    {
        return $this->sendRequest('GET', '/query', ['query' => [
            'q' => $query
        ]]);
    }

    /**
     * Get record.
     *
     * @param string $type
     * @param string $id
     * @param array  $fields
     *
     * @return bool|mixed
     */
    public function getRecord($type, $id, array $fields = [])
    {
        if (!$id) {
            return false;
        }

        $response = $this->sendRequest('GET', "/sobjects/$type/$id", ['query' => $fields]);

        if (!$response) {
            return false;
        }

        return $response;
    }

    /**
     * Get record.
     *
     * @param       $id
     * @param array $fields
     *
     * @return bool|mixed
     */
    public function get($id, array $fields = [])
    {
        return $this->getRecord($this->objName, $id, $fields);
    }

    /**
     * Create record.
     *
     * @param       $type
     * @param array $data
     *
     * @throws SalesforceException
     *
     * @return bool|mixed
     */
    public function createRecord($type, array $data)
    {
        $response = $this->sendRequest('POST', "/sobjects/$type", [
            'json' => $data,
        ]);

        if (!$response) {
            return false;
        }

        if ($response->success !== true) {
            throw new SalesforceException($response->errors);
        }

        return $response;
    }

    /**
     * Update record.
     *
     * @param       $type
     * @param       $id
     * @param array $data
     *
     * @throws SalesforceException
     *
     * @return bool|mixed
     */
    public function updateRecord($type, $id, array $data)
    {
        if (!$id) {
            return false;
        }

        $response = $this->sendRequest(
            'PATCH',
            "/sobjects/$type/$id",
            [
                'json' => $data,
            ]
        );

        if (!$response) {
            return false;
        }

        if ($response->success !== true) {
            throw new SalesforceException($response->errors);
        }

        return $response;
    }

    /**
     * Delete.
     *
     * @param $type
     * @param $id
     *
     * @throws SalesforceException
     *
     * @return bool|mixed
     */
    public function deleteRecord($type, $id)
    {
        $response = $this->sendRequest('DELETE', "/sobjects/$type/$id");

        if (!$response) {
            return false;
        }

        if ($response->success !== true) {
            throw new SalesforceException($response->errors);
        }

        return $response;
    }

    /**
     * Update.
     *
     * @param $id
     * @param $params
     *
     * @return bool|mixed
     */
    public function update($id, array $params)
    {
        return $this->updateRecord($this->objName, $id, $params);
    }

    /**
     * Insert new account.
     *
     * @param $params
     *
     * @return bool
     */
    public function insert($params)
    {
        return $this->createRecord($this->objName, $params);
    }

    /**
     * Delete.
     *
     * @param $params
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->deleteRecord($this->objName, $id);
    }

    public function __call($method, $args)
    {
        if(0 === strpos($method, 'create')) {
            callCreateOnObject($method, $args);
        } elseif (0 === strpos($method, 'update')) {
            callUpdateOnObject($method, $args);
        } elseif (0 === strpos($method, 'delete')) {
            callDeleteOnObject($method, $args);
        } elseif (0 === strpos($method, 'get')) {
            callGetOnObject($method, $args);
        }
    }

    private function callCreateOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\'.$type;
        if(class_exists($class)) {
            $object = {new $class}->createRecord($args[0]);
        } else {
            $this->createRecord($type, $args[0]);
        }
    }

    private function callUpdateOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\'.$type;
        if(class_exists($class)) {
            $object = {new $class}->updateRecord($args[0]);
        } else {
            $this->updateRecord($type, $args[0]);
        }
    }

    private function callDeleteOnObject($method, $args)
    {
        $type = substr($method, 6);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\'.$type;
        if(class_exists($class)) {
            $object = {new $class}->deleteRecord($args[0]);
        } else {
            $this->deleteRecord($type, $args[0]);
        }
    }

    private function callGetOnObject($method, $args)
    {
        $type = substr($method, 3);
        $class = '\\Surge\\LaravelSalesforce\\Objects\\'.$type;
        if(class_exists($class)) {
            $object = {new $class}->getRecord($args[0]);
        } else {
            $this->getRecord($type, $args[0]);
        }
    }

    /**
     * Run report.
     *
     * @param $params
     *
     * @return mixed
     */
    public function runReport($params)
    {
        return $this->sendRequest(
            'GET',
            '/analytics/reports/'.$params['id'],
            ['query' => ['includeDetails' => $params['includeDetails']]],
            false
        );
    }
}
