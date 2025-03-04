<?php

namespace Database\Seeders;

use App\Models\member_results_at_station;
use App\Models\team_at_station;
use App\Models\team_member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberResultsAtStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $teamsAtStations = team_at_station::all();

        // foreach ($teamsAtStations as $teamAtStation) {
        //     $teamMembers = team_member::where('teamId', $teamAtStation->teamId)->get();

        //     foreach ($teamMembers as $teamMember) {
        //         member_results_at_station::factory()->create([
        //             'teamAtStationId' => $teamAtStation->id,
        //             'teamMemberId' => $teamMember->id,
        //         ]);
        //     }
        // }
    }
}
