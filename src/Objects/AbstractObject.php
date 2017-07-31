<?php

namespace Surge\LaravelSalesforce\Objects;

use Surge\LaravelSalesforce\Events\RequestSent;
use Surge\LaravelSalesforce\Events\ResponseReceived;
use Surge\LaravelSalesforce\Exceptions\SalesforceException;
use Surge\LaravelSalesforce\Salesforce;

abstract class AbstractObject implements ObjectInterface
{
    protected $salesforce;

    public function __construct(Salesforce $salesforce)
    {
        $this->salesforce = $salesforce;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return object
     */
    protected function sendRequest(string $method, string $url, array $options = [])
    {
        event(new RequestSent([
            'data' => $options,
            'url'     => $url,
            'class'   => get_class($this),
            'type'    => 'REQUEST',
        ]));

        $response = json_decode(
            $this->salesforce->client->request($method, $this->salesforce->baseUrl . $url, $options)
                ->getBody());

        event(new ResponseReceived([
            'data' => $response,
            'url'     => $url,
            'class'   => get_class($this),
            'type'    => 'RESPONSE',
        ]));

        return $response;
    }

    protected function getType()
    {
        if (isset($this->type)) {
            return $this->type;
        }

        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Get latest version.
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->sendRequest('GET', $this->salesforce->instanceUrl . '/services/data');
    }

    /**
     * Get all organisation limits.
     */
    public function listOrganisationLimits()
    {
        return $this->sendRequest('GET', $this->salesforce->instanceUrl . $this->version['url'] . '/limits');
    }

    /**
     * List all available resources.
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
        return $this->sendRequest('GET', '/sobjects');
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
        return $this->sendRequest('GET', '/sobjects/' . $objectName . '/describe');
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
        return $this->sendRequest('GET', '/sobjects/' . $objectName);
    }

    /**
     * Run report.
     *
     * @param string $id
     * @param bool   $includeDetails
     * @return mixed
     *
     */
    public function runReport(string $id, bool $includeDetails)
    {
        return $this->sendRequest(
            'GET',
            '/analytics/reports/' . $id,
            ['query' => ['includeDetails' => $includeDetails]]
        );
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
        return $this->sendRequest('GET', '/query', [
            'query' => [
                'q' => $query,
            ],
        ]);
    }

    /**
     * Get record.
     *
     * @param string $id
     *
     * @param array  $fields
     */
    public function get(string $id, array $fields = [])
    {
        return $this->sendRequest('GET', "/sobjects/" . $this->getType() . "/$id", ['query' => $fields]);
    }

    /**
     * Update.
     *
     * @param string $id
     * @param        $params
     * @throws SalesforceException
     */
    public function update(string $id, array $params)
    {
        $response = $this->sendRequest('PATCH', "/sobjects/" . $this->getType() . "/$id",
            [
                'json' => $params,
            ]
        );

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
     * @throws SalesforceException
     */
    public function create(array $params)
    {
        $response = $this->sendRequest('POST', "/sobject/" . $this->getType(), [
            'json' => $params,
        ]);

        if ($response->success !== true) {
            throw new SalesforceException($response->errors);
        }

        return $response;
    }

    /**
     * Delete a given record
     *
     * @param string $id
     * @throws SalesforceException
     */
    public function delete(string $id)
    {
        $response = $this->sendRequest('DELETE', "/sobjects/" . $this->getType() . "/$id");

        if ($response->success !== true) {
            throw new SalesforceException($response->errors);
        }

        return $response;
    }
}
