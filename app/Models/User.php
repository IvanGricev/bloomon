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
        return $this->belongsToMany(Subscription::class)
            ->using(SubscriptionUser::class)
            ->withPivot(['last_payment_date', 'subscription_end_date', 'status'])
            ->withTimestamps();
    }

    public function getActiveSubscriptions()
    {
        return $this->subscriptions()
            ->wherePivot('status', 'active')
            ->wherePivot('subscription_end_date', '>', now())
            ->get();
    }

    public function getExpiringSubscriptions()
    {
        return $this->subscriptions()
            ->wherePivot('status', 'active')
            ->wherePivot('subscription_end_date', '>', now())
            ->wherePivot('subscription_end_date', '<=', now()->addDays(7))
            ->get();
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