<?php

namespace Database\Seeders;

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
        $data = [
            ['id' => 1, 'teamId' => 1, 'name' => 'test', 'captain' => 1],
        ];

        if (team_member::count() === 0) {
            team_member::factory()->createMany($data);
        }
    }
}
