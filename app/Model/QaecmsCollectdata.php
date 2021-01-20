<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsCollectdata extends Model
{
    protected $fillable = ['title', 'introduction', 'seokey', 'thumbnail', 'sid', 'stid', 'stype', 'lang', 'area', 'year', 'note', 'score', 'actor', 'director', 'shost', 'last', 'content', 'editor', 'onlykey', 'type'];

    public function source()
    {
        return $this->belongsTo(QaecmsJob::class, 'shost', 'api');
    }

}
