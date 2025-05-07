<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use App\Models\Category;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        // Акция "Весенние букеты 50%"
        $promotion1 = Promotion::create([
            'name'        => 'Весенние букеты 50%',
            'description' => 'Скидка 50% на все Весенние букеты.',
            'discount'    => 50,
            'start_date'  => now()->subDay(),
            'end_date'    => now()->addDays(7),
        ]);
        $springCategoryIds = Category::where('name', 'like', '%Весенние букеты%')->pluck('id')->toArray();
        if (empty($springCategoryIds)) {
            // Если нет точного совпадения, выбираем несколько категорий случайным образом
            $springCategoryIds = Category::inRandomOrder()->limit(3)->pluck('id')->toArray();
        }
        $promotion1->categories()->sync($springCategoryIds);
        
        // Акция "Летние букеты 30%"
        $promotion2 = Promotion::create([
            'name'        => 'Летние букеты 30%',
            'description' => 'Скидка 30% на все Летние букеты.',
            'discount'    => 30,
            'start_date'  => now()->subDay(),
            'end_date'    => now()->addDays(10),
        ]);
        $summerCategoryIds = Category::where('name', 'like', '%Летние букеты%')->pluck('id')->toArray();
        if (empty($summerCategoryIds)) {
            $summerCategoryIds = Category::inRandomOrder()->limit(3)->pluck('id')->toArray();
        }
        $promotion2->categories()->sync($summerCategoryIds);
    }
}