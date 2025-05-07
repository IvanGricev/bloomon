<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $subscriptions = [
            [
                'name' => 'Романтические букеты – Месячная подписка',
                'subscription_type' => 'Романтические',
                'frequency' => 'daily',
                'period' => 'month',
                'price' => 1500.00,
                'description' => 'Ежедневная доставка нежных романтических букетов для поднятия настроения.'
            ],
            [
                'name' => 'Романтические букеты – Годовая подписка',
                'subscription_type' => 'Романтические',
                'frequency' => 'weekly',
                'period' => 'year',
                'price' => 16000.00,
                'description' => 'Еженедельная доставка эксклюзивных романтических букетов весь год.'
            ],
            [
                'name' => 'Свадебные букеты – Месячная подписка',
                'subscription_type' => 'Свадебные',
                'frequency' => 'weekly',
                'period' => 'month',
                'price' => 2000.00,
                'description' => 'Еженедельная доставка свадебных композиций для подготовки к важному дню.'
            ],
            [
                'name' => 'Офисные букеты – Месячная подписка',
                'subscription_type' => 'Офисные',
                'frequency' => 'daily',
                'period' => 'month',
                'price' => 1200.00,
                'description' => 'Ежедневная доставка стильных офисных композиций для улучшения атмосферы в офисе.'
            ],
            [
                'name' => 'Эксклюзивные букеты – Годовая подписка',
                'subscription_type' => 'Эксклюзивные',
                'frequency' => 'weekly',
                'period' => 'year',
                'price' => 25000.00,
                'description' => 'Еженедельная доставка уникальных авторских композиций для истинных ценителей.'
            ],
        ];

        foreach ($subscriptions as $subscription) {
            Subscription::create($subscription);
        }
    }
}