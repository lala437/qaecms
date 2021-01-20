<?php

namespace App\Listeners;

use App\Events\UserLoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(UserLoginEvent $event)
    {
        $user = $event->user;
        $user_id = $user->id;
        $login_time = date('Y-m-d H:i:s', time());
        if (request()->server('HTTP_X_FORWARDED_FOR')) {
            $login_ip = request()->server('HTTP_X_FORWARDED_FOR');
        } else {
            $login_ip = request()->getClientIp();
        }
        DB::table('qaecms_users')->where(['id'=>$user_id])->update(['logintime' => $login_time, 'loginip' => $login_ip,  'lastlogintime' => $user->logintime, 'lastloginip' => $user->loginip]);
    }
}
