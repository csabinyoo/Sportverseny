<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'test',
                'location' => 'test',
                'weighting' => 1,
                'moreIsBetter' => 1,
                'typeId' => 1,
                'userId' => 1,
                'competitionId' => 1
            ],
        ];

        if (Station::count() === 0) {
            Station::factory()->createMany($data);
        }
    }
}
