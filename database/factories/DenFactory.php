<?php

namespace Database\Factories;

use App\Models\Den;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Den>
 */
class DenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['My Cozy Nest', 'Lovely den', 'Chicken Paradise', 'Feathered Haven']),
            'created_date' => $this->faker->date(),
            'user_id' => $this->faker->uuid(),
        ];
    }
}
