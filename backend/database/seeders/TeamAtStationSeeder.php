<?php

namespace Database\Seeders;

use App\Models\Station;
use App\Models\team;
use App\Models\team_at_station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamAtStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = team::all();
        $stations = Station::all();

        foreach ($teams as $team) {
            foreach ($stations as $station) {
                team_at_station::factory()->create([
                    'teamId' => $team->id,
                    'stationId' => $station->id
                ]);
            }
        }
    }
}
