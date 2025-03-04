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
        if (team_at_station::count() === 0) {
            team_at_station::factory(10)->create();
        }
    }
}
