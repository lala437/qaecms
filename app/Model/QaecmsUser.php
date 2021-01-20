<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class QaecmsUser extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name', 'nick', 'email', 'password', 'registerip','vip','integral','status','vip_endtime'];


    /**
     * @var string[]
     *隐藏字段
     */
    protected $hidden = ['remember_token'];

    /**
     * @param $value
     * 密码加密
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }
}
