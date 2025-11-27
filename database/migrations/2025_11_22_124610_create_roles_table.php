<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        DB::table('roles')->insert([
            ['name'=>'admin', 'description'=>'Полные права'],
            ['name'=>'user','description'=>'Пользователь. Может что то купить'],
            ['name'=>'manager','description'=>'Может добавлять и удалять товары'],
            ['name'=>'content','description'=>'Может изменять наполнение по контенту']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
