S<?php

namespace NotificationChannels\WebPush;

use Illuminate\Support\ServiceProvider;

class SalesforceServiceProvider extends ServiceProvider
{

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

        $this->publishes([
            __DIR__.'/../Events/SalesforceLog.php' => app_path('Events/SalesforceLog.php'),
        ]);

        $this->publishes([
            __DIR__.'/../Listeners/StoreSalesforceLog.php.php' => app_path('Listeners/StoreSalesforceLog.php'),
        ]);
    }
}
