<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['order_id', 'menu_item_id', 'quantity', 'status', 'kitchen_hidden', 'kitchen_hidden_by'];

    protected $casts = [
        'kitchen_hidden' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function kitchenHiddenBy()
    {
        return $this->belongsTo(User::class, 'kitchen_hidden_by');
    }
}
