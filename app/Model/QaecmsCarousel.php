<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsCarousel extends Model
{
    protected $fillable = ['title','image','href','location','status','sort'];
}
