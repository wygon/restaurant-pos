<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['number', 'capacity', 'status'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
