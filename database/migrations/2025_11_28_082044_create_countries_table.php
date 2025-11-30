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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('reduction')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        DB::table('countries')->insert([
            ['name'=>"Россия","reduction"=>'RU','description'=>'Российская федерация'],
            ['name'=>"Казахстан","reduction"=>'KZ','description'=>'Респу́блика Казахста́н'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
