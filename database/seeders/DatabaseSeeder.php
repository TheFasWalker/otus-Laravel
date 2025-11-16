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
        // User::factory(10)->create();

        // User::factory()->create([
            // 'name' => 'Test User',
            // 'email' => 'test@example.com',
        // ]);
        $adminRoleId = DB::table('role')->where('name', 'admin')->value('id');
        $userRoleId = DB::table('role')->where('name','user')->value('id');

        User::updateOrCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'login'=>'admin',
            'password' => bcrypt('password'),
            'role_id' => $adminRoleId,
        ]);
        User::factory(10)->create([
            'role_id' => $userRoleId,
        ]);

    }
}
