<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_type',
        'frequency',
        'next_delivery_date',
    ];

    // Подписка принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}