<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class QaecmsArticle extends Model
{
    use Searchable;
    protected $fillable = ['title', 'type', 'introduction', 'seokey', 'thumbnail', 'content', 'editor', 'status', 'vip', 'integral', 'visitors'];

    public function type()
    {
        return $this->belongsTo(QaecmsType::class, 'type', 'id');
    }
}
