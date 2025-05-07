<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_type',
        'frequency',
        'period',
        'price',
        'description',
    ];

    // Связь many-to-many: подписка доступна для многих пользователей
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'subscription_user')->withTimestamps();
    }
}