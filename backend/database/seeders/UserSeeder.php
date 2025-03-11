<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if (User::count() === 0) {
        //     User::factory(count: 150)->create();
        // }
        $filePath = database_path('csv\users.csv');
        // $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ";");
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data[] = [
                    'username' => $row[0],
                    'name' => $row[1],
                    'email' => $row[2],
                    // 'password' => $row[3],
                    'password' => bcrypt($row[3]),
                    'roleId' => $row[4]
                ];
            }
            fclose($handle);
        }
    
        if (User::count() === 0) {
            User::factory()->createMany($data);
        }
    }
}
