<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $data = [
        //     [
        //         'id' => 1,
        //         'name' => 'test',
        //         'date' => '2025-02-16',
        //         'location' => 'test',
        //         'registerFrom' => '2025-02-11',
        //         'registerTo' => '2025-02-15'
        //     ],
        // ];

        // if (Competition::count() === 0) {
        //     Competition::factory()->createMany($data);
        // }
        if (Competition::count() === 0) {
            Competition::factory(10)->create();
        }
    }
}
