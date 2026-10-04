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
        Schema::create('chicken_trait', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chicken_id')->constrained()->cascadeOnDelete();
            // constrained() would guess "traits", but the table is chicken_traits. The
            // trait_id column keeps its name, so the table is named explicitly.
            $table->foreignId('trait_id')
                ->constrained('chicken_traits')
                ->cascadeOnDelete();

            // The same trait on the same chicken twice would be meaningless.
            $table->unique(['chicken_id', 'trait_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chicken_trait');
    }
};
