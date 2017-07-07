<?php

namespace SalesforceHelper;

use SalesforceHelper\Exceptions\SalesforceException;

use GuzzleHttp\Client;

class EnterpriseClient
{
    /**
     * @var mixed
     */
    protected $leadRecord;

    /**
     * @var mixed
     */
    protected $accountRecord;

    /**
     * @var mixed
     */
    protected $opportunityRecord;

    /**
     * @var mixed
     */
    protected $taskRecord;

    /**
     * @var mixed
     */
    protected $brandName;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $instanceUrl;

    /**
     * @var string
     */
    private $id;

    /**
     * @var mixed
     */
    private $issuedAt;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    protected $objName;

    /**
     * @var array
     */
    private $version = [
        'label'   => 'Summer 16',
        'url'     => '/services/data/v37.0',
        'version' => '38.0',
    ];

    /**
     * EnterpriseClient constructor.
     */
    public function __construct()
    {
        $this->leadRecord = config('salesforce.leadrecordtypeid');
        $this->accountRecord = config('salesforce.accountrecordtypeid');
        $this->opportunityRecord = config('salesforce.oppurtunityrecordtypeid');
        $this->taskRecord = config('salesforce.taskrecordtypeid');
        $this->brandName = config('salesforce.brand');

        $this->client = new Client([
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @return bool
     */
    private function isLoggedIn(): bool
    {
        return $this->accessToken !== null;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     * @param bool   $logRequest
     * @return mixed
     */
    private function sendRequest(string $method, string $url, array $options = [], $logRequest = true)
    {
        if (!$this->isLoggedIn()) {
            $this->login();
        }

        if (config('app.env') === 'local') {
            return false;
        }

        if ($logRequest) {
            activity()
                ->withProperties([
                    'ip_address'   => request()->ip(),
                    'user_agent'   => getUserAgent(),
                    'request_body' => $options,
                ])
                ->log('Salesforce - ' . get_class($this) . ' - REQUEST URL:' . $url . '');
        }

        $defaultOptions = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'X-PrettyPrint' => '1',
                'Accept'        => 'application/json',
            ],
        ];

        $requestOptions = array_merge($defaultOptions, $options);

        $response = $this->client->request($method, $this->url . $url, $requestOptions)->getBody()->getContents();

        if ($logRequest) {
            activity()
                ->withProperties([
                    'ip_address'    => request()->ip(),
                    'user_agent'    => getUserAgent(),
                    'response_body' => $response ? \GuzzleHttp\json_decode($response) : '',
                ])
                ->log('Salesforce - ' . get_class($this) . ' - RESPONSE URL:' . $url . '');
        }

        if (!$response) {
            return null;
        }

        return \GuzzleHttp\json_decode($response);
    }

    /**
     * Login
     */
    public function login()
    {
        $body = [
            'grant_type'    => 'password',
            'client_id'     => config('salesforce.client_id'),
            'client_secret' => config('salesforce.client_secret'),
            'username'      => config('salesforce.username'),
            'password'      => config('salesforce.password'),
        ];


        $response = $this->client->post('https://login.salesforce.com/services/oauth2/token', [
            'form_params' => $body,
        ])->getBody()->getContents();


        $responseObject = \GuzzleHttp\json_decode($response);

        $this->id = $responseObject->id;
        $this->issuedAt = $responseObject->issued_at;
        $this->signature = $responseObject->signature;
        $this->accessToken = $responseObject->access_token;
        $this->instanceUrl = $responseObject->instance_url;
        $this->url = $responseObject->instance_url . $this->version['url'];
    }

    /**
     * Get version
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->sendRequest('GET', $this->instanceUrl . '/services/data');
    }

    public function listOrganisationLimits()
    {
        return $this->sendRequest('GET', $this->instanceUrl . $this->version['url'] . '/limits');
    }

    /**
     * List
     *
     * @return mixed
     */
    public function listAvailableResources()
    {
        return $this->sendRequest('GET', '');
    }

    /**
     * List
     *
     * @return mixed
     */
    public function listObjects()
    {
        return $this->sendRequest('GET', '/sobjects', [], false);
    }

    /**
     * Describe
     *
     * @param $objectName
     * @return mixed
     */
    public function describeObject($objectName)
    {
        return $this->sendRequest('GET', '/sobjects/' . $objectName . '/describe', [], false);
    }

    /**
     * Describe
     *
     * @param $objectName
     * @return mixed
     */
    public function describeBasicObject($objectName)
    {
        return $this->sendRequest('GET', '/sobjects/' . $objectName);
    }

    /**
     * Run Salesforce query
     *
     * @param $query
     * @return mixed
     */
    public function query($query)
    {
        return $this->sendRequest('GET', '/query/?q=' . $query);
    }

    /**
     * Get record
     *
     * @param  String $type
     * @param  String $id
     * @param  array  $fields
     * @return Bool|mixed
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
     * Get record
     *
     * @param             $id
     * @param  array      $fields
     * @return bool|mixed
     */
    public function get($id, array $fields = [])
    {
        return $this->getRecord($this->objName, $id, $fields);
    }

    /**
     * Create record
     *
     * @param        $type
     * @param  array $data
     * @return bool|mixed
     * @throws SalesforceException
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
     * Update record
     *
     * @param       $type
     * @param       $id
     * @param array $data
     * @return bool|mixed
     * @throws SalesforceException
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
     * Delete
     *
     * @param $type
     * @param $id
     * @return bool|mixed
     * @throws SalesforceException
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
     * Update
     *
     * @param $id
     * @param $params
     * @return bool|mixed
     */
    public function update($id, array $params)
    {
        return $this->updateRecord($this->objName, $id, $params);
    }

    /**
     * Insert new account
     *
     * @param $params
     * @return bool
     */
    public function insert($params)
    {
        return $this->createRecord($this->objName, $params);
    }

    /**
     * Delete
     *
     * @param $params
     * @return bool
     */
    public function delete($id)
    {
        return $this->deleteRecord($this->objName, $id);
    }

    /**
     * Run report
     *
     * @param $params
     * @return mixed
     */
    public function runReport($params)
    {
        return $this->sendRequest(
            'GET',
            '/analytics/reports/' . $params['id'],
            ['query' => ['includeDetails' => $params['includeDetails']]],
            false
        );
    }
}
