<?php

namespace App\Libraries\IotaCodes;

use Illuminate\Support\ServiceProvider;

class IotaCodesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('IotaCodesClient', function ($app) {
            return Client::create();
        });
    }
}
