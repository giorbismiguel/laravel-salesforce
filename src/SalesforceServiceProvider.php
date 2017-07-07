S<?php

namespace NotificationChannels\WebPush;

use Illuminate\Support\ServiceProvider;

class WebPushServiceProvider extends ServiceProvider
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
    }
}
