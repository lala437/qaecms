<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsJob extends Model
{
    protected $fillable = ['name', 'method', 'lasttime', 'status', 'api', 'bindstatus'];

    public function setApiAttribute($value)
    {
        return $this->attributes['api'] = trim($value);
    }
}
