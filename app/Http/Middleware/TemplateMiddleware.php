<?php

namespace App\Http\Middleware;

use Closure;

class TemplateMiddleware
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
        $view = app('view')->getFinder();
        $view->prependLocation(template_path());
        return $next($request);
    }
}
