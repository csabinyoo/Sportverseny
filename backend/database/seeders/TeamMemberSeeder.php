<?php

namespace Database\Seeders;

use App\Models\team;
use App\Models\team_member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = team::all();

        foreach ($teams as $team) {
            team_member::factory()->count(10)->create([
                'teamId' => $team->id
            ]);
        }
    }
}
