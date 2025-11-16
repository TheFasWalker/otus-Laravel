<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');
        $usersId = DB::table('users')->pluck('id')->toArray();
        if(empty($usersId)){
            $this->command->error('Нет пользователей в базе');
            return;
        }
        $posts=[];
        for ($i = 0; $i < 20; $i++){
            $posts[] = [
                'title'=> $faker->word(),
                'content'=>$faker->realText(500),
                'created_at'=> $faker->dateTimeBetween('-1 year'),
                'updated_at'=>now()
            ];
        }
        DB::table('posts')->insert($posts);

        $postsIds = DB::table('posts')->pluck('id')->toArray();

        $relations = [];

        foreach ($postsIds as $postId){
            $authorCount = rand(1, min(3, count($usersId)));
            $selectedAuthors = array_rand(array_flip($usersId), $authorCount);

            if(!is_array($selectedAuthors)){
                $selectedAuthors = [$selectedAuthors];
            }
            foreach($selectedAuthors as $authorId){
                $relations[] =[
                    'post_id'=>$postId,
                    'user_id'=>$authorId,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ];
            }

            if(count($relations) >= 10){
                DB::table('post_user')->insert($relations);
            }
        }
        if(!empty($relations)){
            DB::table('post_user')->insert($relations);
        }

        $this->command->info('сидирование постов завершено');
    }
}
