<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubscriptionUser extends Pivot
{
    protected $table = 'subscription_user';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'last_payment_date',
        'subscription_end_date',
        'status'
    ];

    protected $casts = [
        'last_payment_date' => 'datetime',
        'subscription_end_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->subscription_end_date > now();
    }

    public function isExpiringSoon(): bool
    {
        return $this->isActive() && $this->subscription_end_date->diffInDays(now()) <= 7;
    }
} 