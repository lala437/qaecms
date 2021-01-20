<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QaecmsShop extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','type','image','desc','number','price','stock','status','vip'];
}
