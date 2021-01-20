<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsDatatomysql extends Model
{
    protected $fillable = ['type', 'metadata', 'nowdata'];

    public function nowtype()
    {
        return $this->belongsTo(QaecmsType::class, 'nowdata', 'id');
    }
}
