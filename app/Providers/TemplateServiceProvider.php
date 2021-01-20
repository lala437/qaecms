<?php

namespace App\Providers;

use App\Model\QaecmsCarousel;
use App\Model\QaecmsSeoConfig;
use App\Model\QaecmsWebConfig;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider
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
        $this->WebConfig();
    }

    private function WebConfig()
    {    $install = public_path('install/lock');
         if(file_exists($install)){
             if (Schema::hasTable('qaecms_web_configs') && Schema::hasTable('qaecms_seo_configs')) {
                 $webconfig = QaecmsWebConfig::first();
                 $seoconfig = QaecmsSeoConfig::first();
                 View::share('__WEBNAME__', $webconfig->name ?? "qaecms");
                 View::share('__WEBSUBTITLE__', $webconfig->subtitle ?? "qaecms");
                 View::share('__WEBDOMIN__', $webconfig->domin ?? "www.qaecms.com");
                 View::share('__WEBLOGO__', $webconfig->logo ?? asset('assets/images/logo.png'));
                 View::share('__WEBICP__', $webconfig->icp ?? "icp123");
                 View::share('__WEBEMAIL__', $webconfig->email ?? "admin@qaecms.com");
                 View::share('__WEBCONTACT__', $webconfig->contact ?? "admin@qaecms.com");
                 View::share('__WEBSTATUS__', $webconfig->status ?? 0);
                 View::share('__WEBSTATISTIC__', $webconfig->statistic??"");
                 View::share('__WEBTEMPLATE__', $webconfig->template ?? "default");
                 View::share('__SEOKEYWORDS__', $seoconfig->keywords ?? "qaecms");
                 View::share('__SEOPICALT__', $seoconfig->picalt ?? "qaecms");
                 View::share('__SEODESCRIPTION__', $seoconfig->description ?? "qaecms");
                 View::share('__SEONOFOLLOW__', isset($seoconfig->nofollow) ? ($seoconfig->nofollow == 1 ? "nofollow" : "") : 1);
             }
         }
    }

}
