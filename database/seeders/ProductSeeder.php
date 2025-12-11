<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Product;
use App\Models\Tag;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countCreatingElem = 30;
        $faker = Factory::create('ru_RU');
        $countries = Country::all();
        $tags = Tag::all();

        if ($countries->isEmpty()) {
            $countryId = DB::table('countries')->insertGetId([
                'name' => 'Россия',
                'code' => 'RU',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $countryId = $countries->random()->id;
        }
        for ($i = 0; $i < $countCreatingElem; $i++) {
            $product = Product::create([
                'name' => $faker->words(rand(2, 5), true),
                'description' => $faker->words(rand(10,20),true),
                'preview' => $faker->randomElement([
                    'product1.jpg',
                    'product2.jpg',
                    'product3.jpg',
                    'product4.jpg',
                    'product5.jpg',
                    null 
                ]),
                'country_id' => $countries->isNotEmpty() ? $countries->random()->id : $countryId,
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
                'user_id'=>1
            ]);
            
            if ($tags->isNotEmpty() && rand(0, 1)) { 
                $randomTags = $tags->random(rand(1, min(3, $tags->count())))->pluck('id')->toArray();
                $product->tags()->attach($randomTags);
            }
        }
        
        $this->command->info('Сидер ProductSeeder завершил работу. Создано ' . $countCreatingElem . ' продуктов.');
    
    }
}
