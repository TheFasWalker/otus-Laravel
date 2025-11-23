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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
        });
        DB::table('tags')->insert([
            ['name'=>'#tag1','description'=>'Описание для 1 тэга'],
            ['name'=>'#tag2','description'=>''],
            ['name'=>'#tag3','description'=>'Описание для 3 тэга'],
            ['name'=>'#tag4','description'=>'']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
