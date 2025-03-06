<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\result_type;
use App\Models\User;
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
        static $usedNames = [];

        $sports = [
            'Football',
            'Basketball',
            'Baseball',
            'Soccer',
            'Tennis',
            'Volleyball',
            'Hockey',
            'Rugby',
            'Cricket',
            'Golf',
            'Swimming',
            'Boxing',
            'MMA',
            'Wrestling',
            'Cycling',
            'Rowing',
            'Fencing',
            'Archery',
            'Skiing',
            'Snowboarding'
        ];

        // Kiszűrjük azokat a neveket, amiket már felhasználtunk
        $availableNames = array_diff($sports, $usedNames);

        // Ha elfogytak az egyedi nevek, újraindítjuk a listát (hogy ne hibázzon)
        if (empty($availableNames)) {
            $usedNames = [];
            $availableNames = $sports;
        }

        // Véletlenszerű, de egyedi nevet választunk
        $name = $this->faker->randomElement($availableNames);
        $usedNames[] = $name;

        return [
            'name' => $name,
            'location' => $this->faker->numberBetween(1, 60) . '. class',
            'weighting' => $this->faker->boolean(50) ? 1.0 : $this->faker->randomFloat(1, 0.5, 0.8),
            'moreIsBetter' => $this->faker->boolean(),
            'typeId' => result_type::inRandomOrder()->first()->id,
            'userId' => User::where('roleId', 2)->inRandomOrder()->first()?->id,
            'competitionId' => Competition::inRandomOrder()->first()->id
        ];
    }
}
