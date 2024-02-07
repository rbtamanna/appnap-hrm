<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\IpLocationService;

class IpLocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IpLocationService::class, function ($app) {
            return new IpLocationService(config('services.ipgeolocation.api_key'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
