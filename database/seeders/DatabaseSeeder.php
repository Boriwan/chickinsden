<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\ChickenTrait;
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
        // Test admin user
        User::factory()->create(['email' => 'boris.bocek@radnom.cz', 'name' => 'Boris Admin', 'password' => 'password', 'is_admin' => true]);
        // Test logged in user
        User::factory()->create(['email' => 'boris.bocek2@radnom.cz', 'name' => 'Boris User', 'password' => 'password', 'is_admin' => false]);

        User::factory(10)->create();

        $breeds = [
            ['name' => 'Leghorn', 'description' => 'A slender, prolific layer known for producing large white eggs.'],
            ['name' => 'Rhode Island Red', 'description' => 'A hardy dual-purpose breed, a firm favourite on small farms.'],
            ['name' => 'Orpington', 'description' => 'A plump, docile breed with a thick plumage and steady temperament.'],
            ['name' => 'Plymouth Rock', 'description' => 'A classic American breed prized for calmness and dependable egg laying.'],
            ['name' => 'Silkie', 'description' => 'A distinctive breed with silk-like plumage, crest, and five toes.'],
            ['name' => 'Australorp', 'description' => 'A black-feathered layer from Australia and an excellent egg producer.'],
            ['name' => 'Wyandotte', 'description' => 'A rose-combed breed with a wide, rounded body and striking patterning.'],
            ['name' => 'Sussex', 'description' => 'An English breed known for pale, speckled eggs and a gentle nature.'],
            ['name' => 'Barnevelder', 'description' => 'A Dutch breed laying strikingly dark brown eggs.'],
            ['name' => 'ISA Brown', 'description' => 'A modern commercial strain famous for very high egg yield.'],
        ];

        foreach ($breeds as $breed) { 
            Breed::firstOrCreate(['name' => $breed['name']], ['description' => $breed['description']]);
        }

        $chickens = Chicken::factory(20)->create();

        ChickenTrait::factory(25)->create();

        foreach ($chickens as $chicken) {
            $chicken->traits()->attach(ChickenTrait::inRandomOrder()->take(rand(0, 3))->pluck('id')->toArray());
        }
    }
}
