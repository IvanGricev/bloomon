<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'order_date',
        'delivery_date',
        'delivery_time_slot',
        'delivery_preferences',
        'payment_method',
        'address',
        'phone'
    ];

    // Заказ принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Заказ состоит из нескольких позиций
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}