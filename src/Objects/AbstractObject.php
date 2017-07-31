<?php

namespace Surge\LaravelSalesforce\Objects;

use Event;
use Surge\LaravelSalesforce\Events\RequestSent;
use Surge\LaravelSalesforce\Events\ResponseReceived;
use Surge\LaravelSalesforce\Exceptions\SalesforceException;

abstract class AbstractObject implements ObjectInterface
{
    protected $recordType;

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return string
     */
    private function sendRequest(string $method, string $url, array $options = []): string
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

        return $response;
    }

    /**
     * Get latest version.
     *
     * @return mixed
     */
    protected function getVersion()
    {
        return $this->sendRequest('GET', $this->auth->instanceUrl.'/services/data');
    }

    /**
     * Get all organisation limits.
     */
    protected function listOrganisationLimits()
    {
        return $this->sendRequest('GET', $this->auth->instanceUrl.$this->version['url'].'/limits');
    }

    /**
     * List all available resources.
     *
     * @return mixed
     */
    protected function listAvailableResources()
    {
        return $this->sendRequest('GET', '');
    }

    /**
     * List all objects.
     *
     * @return mixed
     */
    protected function listObjects()
    {
        return $this->sendRequest('GET', '/sobjects');
    }

    /**
     * Describe an object.
     *
     * @param $objectName
     *
     * @return mixed
     */
    protected function describeObject($objectName)
    {
        return $this->sendRequest('GET', '/sobjects/'.$objectName.'/describe');
    }

    /**
     * Describe basic object.
     *
     * @param $objectName
     *
     * @return mixed
     */
    protected function describeBasicObject($objectName)
    {
        return $this->sendRequest('GET', '/sobjects/'.$objectName);
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
            '/analytics/reports/' . $params['id'],
            ['query' => ['includeDetails' => $params['includeDetails']]]
        );
    }

    /**
     * Run Salesforce query.
     *
     * @param $query
     *
     * @return mixed
     */
    protected function query($query)
    {
        return $this->sendRequest('GET', '/query', ['query' => [
            'q' => $query,
        ]]);
    }

    /**
     * Get record.
     *
     * @param string $id
     *
     * @param array  $fields
     * @return bool|mixed
     */
    public function get(string $id, array $fields = [])
    {
        if (!$id) {
            return false;
        }

        $response = $this->sendRequest('GET', "/sobjects/" . $this->getType() . "/$id", ['query' => $fields]);

        if (!$response) {
            return false;
        }

        return $response;
    }

    /**
     * Update.
     *
     * @param string $id
     * @param $params
     * @return bool|mixed
     * @throws SalesforceException
     */
    public function update(string $id, array $params)
    {
        if (!$id) {
            return false;
        }

        $response = $this->sendRequest(
            'PATCH',
            "/sobjects/$this->recordType/$id",
            [
                'json' => $params,
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
     * Insert new account.
     *
     * @param $params
     *
     * @return bool
     * @throws SalesforceException
     */
    public function create(array $params)
    {
        $response = $this->sendRequest('POST', "/sobject/" . $this->getType(), [
            'json' => $params,
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
     * Delete a given record
     *
     * @param string $id
     * @return bool
     * @throws SalesforceException
     */
    public function delete(string $id)
    {
        $response = $this->sendRequest('DELETE', "/sobjects/" . $this->getType() ."/$id");

        if (!$response) {
            return false;
        }

        if ($response->success !== true) {
            throw new SalesforceException($response->errors);
        }

        return $response;
    }

    protected function getType()
    {
        if (isset($this->type)) {
            return $this->type;
        }

        return get_class($this);
    }
}
