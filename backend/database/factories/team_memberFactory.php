<?php

namespace Database\Factories;
use App\Models\team;
use Faker\Factory as Faker;

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
        $faker =  Faker::create('hu_HU');
        $teamId = team::inRandomOrder()->first()->id;

        $neme = fake()->boolean();
        if ($neme) {
            //férfi
            $nev = $faker->lastName(). " " . $faker->firstNameMale();
        } else {
            $nev = $faker->lastName(). " " . $faker->firstNameFemale();
            //nő
        }

        return [
            'teamId' => $teamId,
            'name' => $nev,
            'captain' => false
        ];
    }
}
