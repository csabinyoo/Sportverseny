<?php

namespace Database\Seeders;

use App\Models\team;
use App\Models\team_member;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('csv\team_members.csv');
        $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ";");
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data[] = [
                    'teamId' => $row[0],
                    'name' => $row[1],
                    'captain' => $row[2]
                ];
            }
            fclose($handle);
        }
    
        if (team_member::count() === 0) {
            team_member::factory()->createMany($data);
        }
    }
}
