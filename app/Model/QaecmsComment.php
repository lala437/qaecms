<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsComment extends Model
{
    protected $fillable = ['pid','name','content'];
}
