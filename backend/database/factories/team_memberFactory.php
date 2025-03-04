<?php

namespace Database\Factories;

use App\Models\team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\team_member>
 */
class team_memberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'teamId' => team::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'captain' => User::inRandomOrder()->first()->id
        ];
    }
}
