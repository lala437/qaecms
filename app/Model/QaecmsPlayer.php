<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsPlayer extends Model
{
    protected $fillable = ['name','type','url','status','sort'];
}
