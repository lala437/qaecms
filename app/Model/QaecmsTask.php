<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsTask extends Model
{
    protected $fillable = ['task','command','lasttime','status'];
}
