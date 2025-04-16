<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    // Если требуется, определите таблицу вручную (по умолчанию используется 'users')
    // protected $table = 'users';

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
        return $this->hasMany(Subscription::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}