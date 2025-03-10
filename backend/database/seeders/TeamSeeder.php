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
        $filePath = database_path('csv\teams.csv');
        $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ";");
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data[] = [
                    'competitionId' => $row[0],
                    'name' => $row[1],
                    'school' => $row[2],
                    'userId' => $row[3]
                ];
            }
            fclose($handle);
        }
    
        if (team::count() === 0) {
            team::factory()->createMany($data);
        }
    }
}
