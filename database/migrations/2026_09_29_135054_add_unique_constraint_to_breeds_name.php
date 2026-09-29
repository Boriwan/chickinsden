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
        $canonicalIds = DB::table('breeds')
            ->selectRaw('MIN(id) as id, name')
            ->groupBy('name')
            ->pluck('id', 'name');

        $canonicalIdByBreedId = DB::table('breeds')
            ->get(['id', 'name'])
            ->mapWithKeys(fn ($breed) => [$breed->id => $canonicalIds[$breed->name]]);

        DB::table('chickens')
            ->orderBy('id')
            ->each(function ($chicken) use ($canonicalIdByBreedId) {
                $canonicalId = $canonicalIdByBreedId[$chicken->breed_id] ?? null;

                if ($canonicalId !== null && $canonicalId !== $chicken->breed_id) {
                    DB::table('chickens')
                        ->where('id', $chicken->id)
                        ->update(['breed_id' => $canonicalId]);
                }
            });

        DB::table('breeds')
            ->whereNotIn('id', $canonicalIds->values()->all())
            ->delete();

        Schema::table('breeds', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breeds', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
