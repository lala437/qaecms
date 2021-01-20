<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QaecmsAnnex extends Model
{
    use SoftDeletes;
    protected $fillable = ['title','type','suffix','content'];
}
