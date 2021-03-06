<?php

namespace Surge\LaravelSalesforce;

use GuzzleHttp\ClientInterface;

class SalesforceAuth
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $accessToken;

    /**
     * @var string
     */
    public $instanceUrl;

    /**
     * @var string
     */
    public $url;

    /**
     * @var bool
     */
    private $authenticated = false;

    private $client;

    /**
     * @var array
     */
    private $version = [
        'label'   => 'Summer 17',
        'url'     => '/services/data/v39.0',
        'version' => '39.0',
    ];

    /**
     * SalesforceAuth constructor.
     *
     * @param $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;

        if (config('laravel-salesforce.disable_on_local') === false) {
            $this->login();
        } else {
            $this->id = '##########';
            $this->accessToken = '@@@@@@';
            $this->instanceUrl = 'http://localhost';
            $this->url = 'http://localhost';
            $this->authenticated = true;

        }
    }

    /**
     * Check if authenticated
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /**
     * Login.
     */
    private function login()
    {
        $authEndpoint = config('laravel-salesforce.auth_endpoint');
        $body = [
            'grant_type'    => 'password',
            'client_id'     => config('laravel-salesforce.client_id'),
            'client_secret' => config('laravel-salesforce.client_secret'),
            'username'      => config('laravel-salesforce.username'),
            'password'      => config('laravel-salesforce.password'),
        ];

        $response = $this->client->post($authEndpoint, [
            'form_params' => $body,
        ])->getBody()->getContents();

        $responseObject = \GuzzleHttp\json_decode($response);

        $this->id = $responseObject->id;
        $this->accessToken = $responseObject->access_token;
        $this->instanceUrl = $responseObject->instance_url;
        $this->url = $responseObject->instance_url . $this->version['url'];

        if ($this->accessToken !== null) {
            $this->authenticated = true;
        }
    }
}
