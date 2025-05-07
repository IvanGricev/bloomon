<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        $promotions = [
            [
                'name'       => 'Весенняя акция',
                'discount'   => 15.00,
                'start_date' => Carbon::now()->subDays(1),
                'end_date'   => Carbon::now()->addDays(10),
            ],
            [
                'name'       => 'Новая коллекция',
                'discount'   => 10.00,
                'start_date' => Carbon::now()->subDays(5),
                'end_date'   => Carbon::now()->addDays(5),
            ],
        ];
        
        foreach ($promotions as $promo) {
            Promotion::create($promo);
        }
    }
}