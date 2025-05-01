<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Цветы', 'Композиции', 'Букеты', 'Подарки'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}