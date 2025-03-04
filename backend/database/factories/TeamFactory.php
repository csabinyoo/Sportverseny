<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\team>
 */
class TeamFactory extends Factory
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
            'competitionId' => Competition::inRandomOrder()->first()->id,
            'name' => $this->faker->word(),
            'school' => $this->faker->city() . 'i ' . $name . ' Szakközép Iskola',
            'userId' => User::inRandomOrder()->first()->id
        ];
    }
}
