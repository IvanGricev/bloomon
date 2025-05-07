<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Свадебные букеты'],
            ['name' => 'Романтические букеты'],
            ['name' => 'Подарочные композиции'],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}