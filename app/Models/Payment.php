<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['order_id', 'amount', 'tip', 'payment_method'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
