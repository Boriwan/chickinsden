<?php

namespace Database\Factories;

use App\Models\ChickenTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChickenTrait>
 */
class ChickenTraitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Bold',
                'Curious',
                'Friendly',
                'Loud',
                'Playful',
                'Shy',
                'Calm',
                'Broody',
                'Chatty',
                'Skittish',
                'Bold and nosy',
                'Gentle giant',
            ]),
        ];
    }
}
