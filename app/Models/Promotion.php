<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    // Если нужны массовые присвоения, добавьте поля в $fillable.
    protected $fillable = ['name', 'description', 'discount', 'start_date', 'end_date'];

    /**
     * Локальный скоуп для фильтрации активных акций.
     */
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }
}