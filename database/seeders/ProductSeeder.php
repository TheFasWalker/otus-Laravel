<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');
        $categories = Category::pluck('id');
        $products = [];
        for ($i = 1; $i <= 20; $i++) {
            $products[] = [
                'title' => $this->generateProductTitle($faker, $i),
                'article' => 'ART-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'description' => $faker->realText(200),
                'preview' => '',
                'count' => $faker->numberBetween(0, 100),
                'discount' => $faker->randomElement([0, 0, 0, 5, 10, 15, 20]), // Чаще без скидки
                'cost' => $faker->randomFloat(2, 100, 10000),
                'category_id' => $faker->randomElement($categories),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
         Product::insert($products);
         $this->command->info('сидирование постов завершено');

    }
    private function generateProductTitle($faker, int $index): string
    {
        $productTypes = [
            'Смартфон', 'Ноутбук', 'Планшет', 'Телевизор', 'Наушники',
            'Фотокамера', 'Игровая консоль', 'Умные часы', 'Монитор', 'Клавиатура',
            'Мышь', 'Колонка', 'Роутер', 'Принтер', 'Жесткий диск',
            'Видеокарта', 'Процессор', 'Материнская плата', 'Оперативная память', 'Блок питания'
        ];

        $brands = [
            'Samsung', 'Apple', 'Xiaomi', 'Huawei', 'Sony',
            'LG', 'Asus', 'Lenovo', 'Acer', 'Dell',
            'HP', 'Canon', 'Epson', 'Logitech', 'Razer',
            'Nvidia', 'Intel', 'AMD', 'WD', 'Seagate'
        ];

        $modelSuffixes = [
            'Pro', 'Max', 'Lite', 'Ultra', 'Plus',
            'GTX', 'RTX', 'Air', 'Note', 'Book'
        ];

        $type = $productTypes[($index - 1) % count($productTypes)];
        $brand = $brands[($index - 1) % count($brands)];
        $model = $faker->randomElement($modelSuffixes);
        $number = $faker->numberBetween(1000, 9999);

        return "{$type} {$brand} {$model} {$number}";
    }
}
