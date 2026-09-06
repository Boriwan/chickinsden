<?php

namespace Database\Factories;

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
            'born_date' => $this->faker->date(),
            'breed_id' => $this->faker->numberBetween(1, 10),
            'height' => $this->faker->randomFloat(2, 25, 60),
            'weight' => $this->faker->randomElement(['light', 'medium', 'heavy']),
            'den_id' => $this->faker->numberBetween(1, 1),

            'image' => fake()->randomElement(Storage::disk('public')->files('chickens_imgs')),
        ];
    }
}
