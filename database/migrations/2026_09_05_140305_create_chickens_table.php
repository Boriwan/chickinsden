<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chickens', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('gender');
            $table->date('birth_date');
            $table->foreignId('breed_id')->constrained('breeds');

            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chickens');
    }
};
