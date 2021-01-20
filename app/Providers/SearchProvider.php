<?php

namespace App\Providers;

use Algolia\AlgoliaSearch\SearchClient as Algolia;
use App\Librarys\Search\QaecmsSearchEngine;
use App\Model\QaecmsSearchConfig;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Console\ImportCommand;
use Laravel\Scout\EngineManager;

class SearchProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $install = public_path('install/lock');
        if(file_exists($install)){
        resolve(EngineManager::class)->extend('qaecms', function () {
            $appid = config('scout.algolia.id');
            $appkey = config('scout.algolia.secret');
            if (Schema::hasTable('qaecms_search_configs')) {
                $algolia = QaecmsSearchConfig::where(['type' => 'algolia'])->first();
                if ($algolia) {
                    $appid = $algolia->arg1;
                    $appkey = $algolia->arg2;
                }
            }
            return new QaecmsSearchEngine(Algolia::create($appid, $appkey), config('scout.soft_delete'));
        });
        $this->commands([ImportCommand::class]);
    }
    }
}
