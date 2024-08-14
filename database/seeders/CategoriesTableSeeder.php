<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Vegetables' => [
                ['name' => 'Tomato', 'image' => 'tomato.jpg', 'price' => 2.50],
                ['name' => 'Onion', 'image' => 'onion.jpg', 'price' => 1.20],
                // ... other vegetables
            ],
            'Fruit' => [
                ['name' => 'Apple', 'image' => 'apple.jpg', 'price' => 3.00],
                ['name' => 'Grapes', 'image' => 'grapes.jpg', 'price' => 2.50],
                // ... other fruits
            ],
            'Snacks' => [
                ['name' => 'Lays', 'image' => 'lays.jpg', 'price' => 1.00],
                ['name' => 'Chocolate', 'image' => 'chocolate.jpg', 'price' => 2.00],
                // ... other snacks
            ],
        ];

        foreach ($categories as $categoryName => $items) {
            $category = Category::create(['name' => $categoryName]);

            foreach ($items as $item) {
                Item::create([
                    'name' => $item['name'],
                    'image' => $item['image'],
                    'price' => $item['price'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
