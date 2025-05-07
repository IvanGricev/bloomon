<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name'        => 'Букет роз',
                'description' => 'Элегантный букет из красных роз, идеально подходящий для романтического вечера.',
                'price'       => 1500.00,
                'category_id' => 1, // Предполагается, что категория с id=1 уже существует
                'photo'       => 'https://via.placeholder.com/300?text=Розы',
            ],
            [
                'name'        => 'Букет тюльпанов',
                'description' => 'Яркий и свежий букет разноцветных тюльпанов для весеннего настроения.',
                'price'       => 1200.00,
                'category_id' => 2,
                'photo'       => 'https://via.placeholder.com/300?text=Тюльпаны',
            ],
            [
                'name'        => 'Композиция из ромашек',
                'description' => 'Нежная композиция из ромашек и зелени для искренних и теплых чувств.',
                'price'       => 1000.00,
                'category_id' => 3,
                'photo'       => 'https://via.placeholder.com/300?text=Ромашки',
            ],
        ];
        
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}