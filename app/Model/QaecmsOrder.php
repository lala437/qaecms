<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QaecmsOrder extends Model
{
    protected $fillable = ['order_id', 'platform', 'platform_id', 'user_id', 'shop_id', 'money', 'currency_type', 'status', 'success_at'];

    public function user()
    {
        return $this->belongsTo(QaecmsUser::class, 'user_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(QaecmsShop::class, 'shop_id', 'id');
    }
}
