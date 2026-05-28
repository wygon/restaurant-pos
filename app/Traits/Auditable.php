<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Auditable
{
    public static function bootAuditable()
    {
        // Kiedy rekord jest tworzony
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        // Kiedy rekord jest edytowany
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        // Kiedy rekord jest usuwany (Soft Delete)
        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }
}