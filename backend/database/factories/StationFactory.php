<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\result_type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'location' => $this->faker->numberBetween(1, 60) . '. terem',
            'weighting' => $this->faker->randomFloat(1, 0.5, 1),
            'moreIsBetter' => $this->faker->boolean(),
            'typeId' => result_type::inRandomOrder()->first()->id,
            'userId' => 1,
            'competitionId' => Competition::inRandomOrder()->first()->id
        ];
    }
}
