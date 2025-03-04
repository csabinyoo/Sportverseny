<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\team_at_station;
use App\Models\team_member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\member_results_at_station>
 */
class member_results_at_stationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teamAtStation = team_at_station::inRandomOrder()->first();
        $station = Station::find($teamAtStation->stationId);
        return [
            'teamAtStationId' => $teamAtStation->id,
            'teamMemberId' => team_member::inRandomOrder()->first()->id,
            'result' => $station->typeId == 1 ? $this->faker->numberBetween(0, 100) : 0,
            'resultTime' => $station->typeId == 2 ? sprintf('00:%02d:%02d', $this->faker->numberBetween(0, 15), $this->faker->numberBetween(0, 59)) : 0,
        ];
    }
}
