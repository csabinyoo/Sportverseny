<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competition>
 */
class CompetitionFactory extends Factory
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

        // $location = $faker->numberBetween(1, 60) . '. terem';

        return [
            'name' => $name . ' emléktorna',
            'date' => now()->addDays(7),
            'location' => $faker->city() . 'i ' . $name . ' Szakközép Iskola',
            'registerFrom' => now(),
            'registerTo' => now()->addDays(6)
        ];
    }
}
