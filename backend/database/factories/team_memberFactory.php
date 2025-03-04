<?php

namespace Database\Factories;

use App\Models\team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

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
        $faker =  Faker::create('hu_HU');

        $neme = fake()->boolean();
        if ($neme) {
            $name = $faker->lastName(). " " . $faker->firstNameMale();
        } else {
            $name = $faker->lastName(). " " . $faker->firstNameFemale();
        }

        return [
            'teamId' => team::inRandomOrder()->first()->id,
            'name' => $name,
            'captain' => 0
        ];
    }
}
