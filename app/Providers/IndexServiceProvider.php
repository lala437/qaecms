<?php

namespace App\Providers;

use App\Librarys\Services\Index\IndexService;
use App\Librarys\Services\Index\PayService;
use App\Librarys\Services\Index\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class IndexServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Librarys\Interfaces\Index\IndexInterface', function ($app) {
            return new IndexService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Index\UserInterface', function ($app) {
            return new UserService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Index\PayInterface', function ($app) {
            return new PayService(Request::capture());
        });
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        //
    }
}
