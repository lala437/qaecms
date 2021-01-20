<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsNav extends Model
{
    protected $fillable = ['pid','title','href','status','sort'];
}
