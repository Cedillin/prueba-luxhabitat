<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Neighborhood;
use App\Models\PropertyCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->numberBetween(100000, 500000),
            'neighborhood_id' => Neighborhood::factory(),
            'building_id' => Building::factory(),
            'property_category_id' => PropertyCategory::factory(),
            'bedrooms' => $this->faker->numberBetween(1, 5),
        ];
    }
}
