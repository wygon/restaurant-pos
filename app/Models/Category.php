<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = ['name', 'is_active'];
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
