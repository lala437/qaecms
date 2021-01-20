<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class QaecmsAdmin extends Authenticatable
{

    use Notifiable;
    /**
     * 白名单
     */

    protected $fillable = ['name','password','email'];


    /**
     * @var string[]
     *隐藏字段
     */
    protected $hidden = ['remember_token'];


    /**
     * @param $value
     * 密码加密
     */
    public function setPasswordAttribute($value){
       $this->attributes['password'] = md5($value);
    }
}
