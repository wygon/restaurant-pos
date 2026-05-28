<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use SoftDeletes, Auditable;
    
    protected $fillable = ['category_id', 'name', 'description', 'price', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
