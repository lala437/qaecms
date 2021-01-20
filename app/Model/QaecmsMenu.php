<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsMenu extends Model
{

    /**
     * @param $value
     * @return string
     * 修改路由
     */
    public function getHrefAttribute($value){

           return $value=="#"?"":rtrim(route('qaecmsadmin.'.$value,"",false),'?');
    }
}
