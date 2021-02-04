<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsWebConfig extends Model
{
    //数据库字段白名单
    protected $fillable = ['id','name','subtitle','domin','logo','icp','email','contact','statistic','template','status'];
}
