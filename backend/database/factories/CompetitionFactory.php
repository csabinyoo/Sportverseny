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

        $minDate = '2015-01-01';
        $maxDate = '2024-12-31';
        $randomDate = fake()->date('Y-m-d', $maxDate);

        $neme = fake()->boolean();
        if ($neme) {
            $name = $faker->lastName(). " " . $faker->firstNameMale();
        } else {
            $name = $faker->lastName(). " " . $faker->firstNameFemale();
        }

        while ($randomDate < $minDate) {
            $randomDate = fake()->date('Y-m-d', $maxDate);
        }

        $randomDateCarbon = Carbon::parse($randomDate);
        $registerFrom = $randomDateCarbon->copy()->subDays(7)->format('Y-m-d');
        $registerTo = $randomDateCarbon->copy()->subDays(1)->format('Y-m-d');

        $location = $faker->numberBetween(1, 60) . '. terem';

        return [
            'name' => $name . ' emléktorna',
            'date' => $randomDate,
            'location' => $location,
            'registerFrom' => $registerFrom,
            'registerTo' => $registerTo
        ];
    }
}
