<?php

namespace Surge\LaravelSalesforce;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class SalesforceServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('salesforce', function ($app) {
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
                    'Accept'        => 'application/json',
                ],
            ]);

            return new Salesforce($client, $auth);
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
            __DIR__.'/../config/sf.php' => config_path('sf.php'),
        ], 'config');
    }

    public function provides()
    {
        return ['salesforce', 'Surge\LaravelSalesforce\Salesforce'];
    }
}
