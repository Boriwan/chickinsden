<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\User;
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
            'birth_date' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'breed_id' => fn () => Breed::inRandomOrder()->value('id') ?? Breed::factory(),
            'height' => $this->faker->randomFloat(2, 25, 60),
            'weight' => $this->faker->randomElement(['light', 'medium', 'heavy']),
            'user_id' => fn () => User::inRandomOrder()->value('id') ?? User::factory(),

            'image' => fake()->randomElement(Storage::disk('public')->files('chickens_imgs')),
        ];
    }
}
