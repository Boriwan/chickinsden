<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Breed::factory(4)->create();

        Chicken::factory(12)->create();
    }
}
