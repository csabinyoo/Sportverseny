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

        static $usedNames = [];

        $teamNames = [
            'Titans', 'Dragons', 'Warriors', 'Thunder', 'Vikings', 'Eagles', 'Panthers', 
            'Hawks', 'Falcons', 'Predators', 'Storm', 'Sharks', 'Wolves', 'Spartans', 'Gladiators'
        ];

        // Kiszűrjük azokat a neveket, amiket már felhasználtunk
        $availableNames = array_diff($teamNames, $usedNames);

        // Ha elfogytak az egyedi nevek, újraindítjuk a listát (hogy ne hibázzon)
        if (empty($availableNames)) {
            $usedNames = [];
            $availableNames = $teamNames;
        }

        // Véletlenszerű, de egyedi nevet választunk
        $name = $this->faker->randomElement($availableNames);
        $usedNames[] = $name;

        return [
            'competitionId' => Competition::inRandomOrder()->first()->id,
            'name' => $name,
            'school' => $this->faker->city() . 'i ' . $name . ' Szakközép Iskola',
            'userId' => User::inRandomOrder()->first()->id
        ];
    }
}
