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
        'quantity',
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
        return $this->hasMany(\App\Models\ProductImage::class);
    }

    // Проверка доступности товара
    public function isAvailable(int $requestedQuantity = 1): bool
    {
        return $this->quantity >= $requestedQuantity;
    }

    // Уменьшение количества товара
    public function decreaseQuantity(int $quantity = 1): bool
    {
        if (!$this->isAvailable($quantity)) {
            return false;
        }

        $this->quantity -= $quantity;
        return $this->save();
    }

    // Увеличение количества товара
    public function increaseQuantity(int $quantity = 1): bool
    {
        $this->quantity += $quantity;
        return $this->save();
    }
}