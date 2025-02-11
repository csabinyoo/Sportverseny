<?php

namespace Database\Seeders;

use App\Models\member_results_at_station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberResultsAtStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'teamAtStationId' => 1,
                'teamMemberId' => 1,
                'result' => 0,
                'resultTime' => '00:02:45',
            ],
        ];

        if (member_results_at_station::count() === 0) {
            member_results_at_station::factory()->createMany($data);
        }
    }
}
