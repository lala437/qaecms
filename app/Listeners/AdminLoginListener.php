<?php

namespace App\Listeners;

use App\Events\AdminLoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminLoginListener
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
    public function handle(AdminLoginEvent $event)
    {
        $admin = $event->admin;
        $admin_id = $admin->id;
        $login_time = date('Y-m-d H:i:s', time());
        if (request()->server('HTTP_X_FORWARDED_FOR')) {
            $login_ip = request()->server('HTTP_X_FORWARDED_FOR');
        } else {
            $login_ip = request()->getClientIp();
        }
        $iparr = explode('.', $login_ip);
        if (Str::contains($iparr[0], ['192', '172', '10', '127'])) {//判断是否为本地或者局域网地址
            $cityname = "本地局域网";
        } else {
            $url = "https://api.map.baidu.com/location/ip?ip=" . $login_ip . "&ak=fhLspwHyadYLtrKuc06WlLXvRKKxo6ZF&coor=bd09ll";
            $jieguo = curl_get($url);
            if (is_array($jieguo)) {
                $cityname = "未知位置";
            } else {
                $res = json_decode($jieguo, 1);//通过百度地图API获取ip对应的城市
                $cityname = $res['content']['address'] ?? "未知位置";
            }
        }
        DB::table('qaecms_admins')->where(['id' => $admin_id])->update(['logintime' => $login_time, 'loginip' => $login_ip, 'loginaddress' => $cityname, 'lastlogintime' => $admin->logintime, 'lastloginip' => $admin->loginip, 'lastloginaddress' => $admin->loginaddress]);
    }
}
