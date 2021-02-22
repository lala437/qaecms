<?php

namespace App\Providers;

use App\Librarys\Services\Admin\AdService;
use App\Librarys\Services\Admin\AnnexService;
use App\Librarys\Services\Admin\CacheService;
use App\Librarys\Services\Admin\CollectService;
use App\Librarys\Services\Admin\CommentService;
use App\Librarys\Services\Admin\DataToMysqlService;
use App\Librarys\Services\Admin\JobService;
use App\Librarys\Services\Admin\LinkService;
use App\Librarys\Services\Admin\PayService;
use App\Librarys\Services\Admin\PlayerService;
use App\Librarys\Services\Admin\SearchService;
use App\Librarys\Services\Admin\ShopService;
use App\Librarys\Services\Admin\SystemService;
use App\Librarys\Services\Admin\ContentService;
use App\Librarys\Services\Admin\MenuService;
use App\Librarys\Services\Admin\TaskService;
use App\Librarys\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Librarys\Interfaces\Admin\MenuInterface', function ($app) {
            return new MenuService();
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\SystemInterface', function ($app) {
            return new SystemService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\ContentInterface', function ($app) {
            return new ContentService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\AnnexInterface', function ($app) {
            return new AnnexService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\JobInterface', function ($app) {
            return new JobService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\DataToMysqlInterface', function ($app) {
            return new DataToMysqlService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\UserInterface', function ($app) {
            return new UserService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\ShopInterface', function ($app) {
            return new ShopService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\PayInterface', function ($app) {
            return new PayService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\SearchInterface', function ($app) {
            return new SearchService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\LinkInterface', function ($app) {
            return new LinkService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\CacheInterface', function ($app) {
            return new CacheService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\PlayerInterface', function ($app) {
            return new PlayerService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\AdInterface', function ($app) {
            return new AdService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\TaskInterface', function ($app) {
            return new TaskService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\CommentInterface', function ($app) {
            return new CommentService(Request::capture());
        });
        $this->app->bind('App\Librarys\Interfaces\Admin\CollectInterface', function ($app) {
            return new CollectService(Request::capture());
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
