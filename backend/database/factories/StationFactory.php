<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\result_type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

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
        $faker = Faker::create('hu_HU');
        static $usedNames = [];

        $sportsByType = [
            1 => ['Labdarúgás', 'Kosárlabda', 'Baseball', 'Röplabda', 'Jégkorong', 'Krikett', 'Golf', 'Birkózás', 'Íjászat'],
            2 => ['Úszás', 'Futás', 'Kerékpározás', 'Evezés', 'Síelés', 'Snowboard', 'Vívás']
        ];

        $typeId = result_type::inRandomOrder()->first()->id;

        $availableSports = array_diff($sportsByType[$typeId] ?? [], $usedNames);

        if (empty($availableSports)) {
            $usedNames = [];
            $availableSports = $sportsByType[$typeId] ?? [];
        }

        $name = $faker->randomElement($availableSports);
        $usedNames[] = $name;

        $userId = User::where('roleId', 2)
            ->inRandomOrder()
            ->first()?->id;

        return [
            'name' => $name,
            'location' => $faker->numberBetween(1, 60) . '. terem',
            'weighting' => $faker->boolean(50) ? 1.0 : $faker->randomFloat(1, 0.5, 0.8),
            'moreIsBetter' => $typeId == 1 ? 1 : 0,
            'typeId' => $typeId,
            'userId' => $userId,
            'competitionId' => Competition::first()->id
            // 'competitionId' => Competition::inRandomOrder()->first()->id // Véletlenszerű verseny ID kiválasztása
        ];
    }
}
