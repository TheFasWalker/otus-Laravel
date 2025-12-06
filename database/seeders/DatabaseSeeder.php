<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('name','admin')->value('id');
        $userRoleId = DB::table('roles')->where('name','user')->value('id');

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role_id' => $adminRoleId,
            ]
        );

        User::factory(10)->create([
            'role_id'=>$userRoleId,
        ]);
        $this->call([
            ProductSeeder::class
        ]);

    }
}
