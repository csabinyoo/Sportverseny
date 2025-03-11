<?php

namespace Database\Seeders;

use App\Models\member_results_at_station;
use App\Models\team_at_station;
use App\Models\team_member;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MemberResultsAtStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMembers = team_member::all();
        $teamAtStations = team_at_station::all();

        $faker =  Faker::create('hu_HU');
        foreach ($teamMembers as $teamMember) {
            foreach ($teamAtStations as $teamAtStation) {
                $teamAtStationId = $teamAtStation['id'];
                $teamMemberId = $teamMember['id'];
                $query =
                    "SELECT s.typeId FROM stations s
                    INNER JOIN team_at_stations t ON s.id = t.stationId
                    WHERE t.id = ?";
                $rows = DB::select($query, [$teamAtStationId]);
                $typeId = $rows[0]->typeId;
                // $typeId = $rows[0]['typeId'];

                if ($typeId == 1) {
                    $result = $faker->numberBetween(0, 100);
                    $resultTime = null;
                } else {
                    $hours = $faker->numberBetween(0, 0);  // Véletlenszerű óra 0 és 23 között
                    $minutes = $faker->numberBetween(0, 2);  // Véletlenszerű perc 0 és 59 között
                    $seconds = $faker->numberBetween(0, 59);  // Véletlenszerű másodperc 0 és 59 között
                
                    // Formázott idő (pl. 00:12:00)
                    $resultTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                    $result = null;
                }

                $data[] = [
                    'teamAtStationId' => $teamAtStationId,
                    'teamMemberId' => $teamMemberId,
                    'result' => $result,
                    'resultTime' => $resultTime
                ];
            //    member_results_at_station::factory()->create([
            //     'teamAtStationId' => $teamAtStationId,
            //     'teamMemberId' => $teamMemberId,
            //     'result' => $result,
            //     'resultTime' => $resultTime
            //    ]);
            }
        }

        if (count($data) > 0) {
            DB::table('member_results_at_stations')->insert($data);
        }
    }
}
