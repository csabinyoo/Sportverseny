<?php

namespace Database\Seeders;

use App\Models\team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'competitionId' => 1, 'name' => 'test', 'school' => 'test', 'userId' => 1],
        ];

        if (team::count() === 0) {
            team::factory()->createMany($data);

        }
    }
}
