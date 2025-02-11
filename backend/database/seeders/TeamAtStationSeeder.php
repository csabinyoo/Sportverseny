<?php

namespace Database\Seeders;

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
        $data = [
            ['id' => 1, 'teamId' => 1, 'stationId' => 1],
        ];

        if (team_at_station::count() === 0) {
            team_at_station::factory()->createMany($data);
        }
    }
}
