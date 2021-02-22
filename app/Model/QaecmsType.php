<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsType extends Model
{
    protected $fillable = ['pid','type','name','sort','status'];

    public function article()
    {
        return $this->hasMany(QaecmsArticle::class, 'type', 'id');
    }
}
