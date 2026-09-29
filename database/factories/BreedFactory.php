<?php

namespace Database\Factories;

use App\Models\Breed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Breed>
 */
class BreedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Dutch Bantam', 'Dominique', 'Dorking', 'Barnevelder', 'Leghorn', 'Plymouth Rock', 'Silkie', 'Sussex', 'Orpington', 'Wyandotte']),
            'description' => $this->faker->text(),
        ];
    }
}
