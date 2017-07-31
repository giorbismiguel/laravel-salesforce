<?php

namespace Surge\LaravelSalesforce;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class SalesforceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('salesforce', function () {
            $authClient = new Client([
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $auth = new SalesforceAuth($authClient);

            $client = new Client([
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer '.$auth->accessToken,
                    'X-PrettyPrint' => '1',
                ],
            ]);

            return new Salesforce($client, $auth->url, $auth->instanceUrl);
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-salesforce.php' => config_path('laravel-salesforce.php'),
        ], 'config');
    }
}
