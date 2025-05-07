<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'photo',
    ];

    // Отношение к категории
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Товары могут входить в состав заказов
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Отзывы к товару
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}