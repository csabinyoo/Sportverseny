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
        $teamMembers = team_member::all();

        foreach ($teamMembers as $member) {
            $teamAtStations = team_at_station::where('teamId', $member->teamId)->get();
        
            foreach ($teamAtStations as $teamAtStation) {
                member_results_at_station::factory()->create([
                    'teamAtStationId' => $teamAtStation->id,
                    'teamMemberId' => $member->id
                ]);
            }
        }
        
    }
}
