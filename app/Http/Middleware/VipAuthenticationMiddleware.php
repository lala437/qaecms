<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class VipAuthenticationMiddleware
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
        $user = Auth::user();
        if ($user) {
            if ($user->vip == 1) {
                $vip_endtime = Carbon::parse($user->vip_endtime);
                $nowtime = Carbon::now();
                if ($vip_endtime->lt($nowtime)) {
                    $user->update(['vip' => 0]);
                }
            }
        }
        return $next($request);

    }
}
