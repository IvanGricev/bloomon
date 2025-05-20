<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    // Если требуется, определите таблицу вручную (по умолчанию используется 'users')
    // protected $table = 'users';

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'role', // Например, "admin" или "client"
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Отношения

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(\App\Models\Subscription::class, 'subscription_user')->withTimestamps();
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
}