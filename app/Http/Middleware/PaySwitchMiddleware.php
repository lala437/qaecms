<?php

namespace App\Http\Middleware;

use App\Model\QaecmsPayConfig;
use Closure;

class PaySwitchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pay = QaecmsPayConfig::first();
        if ($pay && $pay->status == 1) {
            return $next($request);
        } else {
            return redirect(route('qaecmsindex.user'));
        }
    }
}
