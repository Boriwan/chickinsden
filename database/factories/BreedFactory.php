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
            'name' => $this->faker->unique()->randomElement([
                'Australorp',
                'Barnevelder',
                'Brahma',
                'Buckeye',
                'Campbell',
                'Cochin',
                'Dominique',
                'Dorking',
                'Easter Egger',
                'Fayoules',
                'Golden Comet',
                'Hamburg',
                'Houdan',
                'ISA Brown',
                'Leghorn',
                'Malay',
                'Naked Neck',
                'New Hampshire',
                'Orpington',
                'Plymouth Rock',
                'Rhode Island Red',
                'Silkie',
                'Sussex',
                'Vorwerk',
                'Wyandotte',
                'Yokohama',
            ]),
            'description' => $this->faker->text(),
        ];
    }
}
