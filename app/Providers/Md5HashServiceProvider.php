<?php

namespace App\Providers;

use App\Helper\Hasher\Md5Hasher;
use Illuminate\Support\ServiceProvider;

class Md5HashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('hash', function () {
            return new Md5Hasher();
        });
    }

    public function provides()
    {
        return ['hash'];
    }
}
