<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsSeoConfig extends Model
{
    //数据库字段白名单
    protected $fillable = ['id','keywords','picalt','description','nofollow'];
}
