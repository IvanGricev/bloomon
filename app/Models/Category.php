<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function promotions()
    {
        return $this->belongsToMany(\App\Models\Promotion::class, 'category_promotion')->withTimestamps();
    }
    
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }
}