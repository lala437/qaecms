<?php

namespace App\Http\Middleware;

use App\Model\QaecmsWebConfig;
use Closure;

class WebSwitchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $switch = QaecmsWebConfig::first()->status;
        if($switch==1){
            return $next($request);
        }else{
            die("网站维护中。。。");
        }
    }
}
