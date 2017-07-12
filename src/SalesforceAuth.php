<?php

namespace Surge\LaravelSalesforce;

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

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function __construct($client)
    {
        $this->client = $client;

        $this->login();
    }

    /**
     * Login.
     */
    private function login()
    {
        $body = [
            'grant_type'    => 'password',
            'client_id'     => config('sf.client_id'),
            'client_secret' => config('sf.client_secret'),
            'username'      => config('sf.username'),
            'password'      => config('sf.password'),
        ];

        $response = $this->client->post('https://login.salesforce.com/services/oauth2/token', [
            'form_params' => $body,
        ])->getBody()->getContents();

        $responseObject = \GuzzleHttp\json_decode($response);

        $this->id = $responseObject->id;
        $this->accessToken = $responseObject->access_token;
        $this->instanceUrl = $responseObject->instance_url;
        $this->url = $responseObject->instance_url.$this->version['url'];

        if ($this->accessToken !== null) {
            $this->authenticated = true;
        }
    }
}
