<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'image'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(SubscriptionUser::class)
            ->withPivot(['last_payment_date', 'subscription_end_date', 'status'])
            ->withTimestamps();
    }

    public function calculateEndDate(\DateTime $startDate): \DateTime
    {
        $endDate = clone $startDate;
        
        switch ($this->period) {
            case 'month':
                $endDate->modify('+1 month');
                break;
            case 'year':
                $endDate->modify('+1 year');
                break;
            default:
                throw new \InvalidArgumentException('Invalid subscription period');
        }
        
        return $endDate;
    }
}