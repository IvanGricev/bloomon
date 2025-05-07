<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'discount',
        'start_date',
        'end_date'
    ];

    // Локальный скоуп для фильтрации активных акций (используется current date)
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    // Отношение many-to-many с категориями
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'category_promotion')->withTimestamps();
    }
}