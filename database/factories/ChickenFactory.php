<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Chicken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Chicken>
 */
class ChickenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birth_date' => $this->faker->date(),
            'breed_id' => fn () => Breed::inRandomOrder()->value('id') ?? Breed::factory(),
            'height' => $this->faker->randomFloat(2, 25, 60),
            'weight' => $this->faker->randomElement(['light', 'medium', 'heavy']),

            'image' => fake()->randomElement(Storage::disk('public')->files('chickens_imgs')),
        ];
    }
}
