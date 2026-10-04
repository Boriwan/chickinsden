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
        // Traits get an icon so they can be recognised at a glance in the
        // picker and on a chicken. Left nullable so a trait created without
        // one still works.
        Schema::table('chicken_traits', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('name');
        });

        // height was created as a varchar, so it held "33.23" as text. SQLite
        // coerced it for aggregate functions but any comparison or arithmetic
        // outside SQL still treated it as a string.
        Schema::table('chickens', function (Blueprint $table) {
            $table->decimal('height', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chickens', function (Blueprint $table) {
            $table->string('height')->nullable()->change();
        });

        Schema::table('chicken_traits', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
