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
            $auth = SalesforceAuth::login();

            $client = new Client([
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return new Salesforce($client);
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
