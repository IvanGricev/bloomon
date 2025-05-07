<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        foreach ($categories as $category) {
            // Для каждой категории создаём 2 товара
            for ($i = 1; $i <= 2; $i++) {
                Product::create([
                    'name'        => $category->name . ' Продукт ' . $i,
                    'description' => 'Описание для ' . $category->name . ' продукта ' . $i,
                    'price'       => rand(1000, 5000),
                    'category_id' => $category->id,
                    'photo'       => null,
                ]);
            }
        }
    }
}