<?php

namespace Database\Factories;

use App\Models\Follow;
use App\Models\User;
use App\Models\Property;
use App\Models\Neighborhood;
use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Follow::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $followableTypes = [
            Property::class,
            Neighborhood::class,
            Building::class,
        ];

        $followableType = $this->faker->randomElement($followableTypes);
        $followableId = match ($followableType) {
            Property::class => Property::all()->random()->id,
            Neighborhood::class => Neighborhood::all()->random()->id,
            Building::class => Building::all()->random()->id,
        };

        return [
            'user_id' => User::all()->random()->id, // assuming you have users in your database
            'followable_id' => $followableId,
            'followable_type' => $followableType,
            'notification_frequency' => $this->faker->randomElement(['daily', 'weekly']),
        ];
    }
}
