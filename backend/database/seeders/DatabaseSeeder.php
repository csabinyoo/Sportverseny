<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // DB::statement('DELETE FROM member_results_at_stations');
        // DB::statement('DELETE FROM team_at_stations');
        // DB::statement('DELETE FROM team_members');
        // DB::statement('DELETE FROM teams');
        // DB::statement('DELETE FROM stations');
        DB::statement('DELETE FROM competitions');
        // DB::statement('DELETE FROM result_types');
        // DB::statement('DELETE FROM users');
        // DB::statement('DELETE FROM roles');

        $this->call([
            // RoleSeeder::class,
            // UserSeeder::class,
            // ResultTypeSeeder::class,
            CompetitionSeeder::class,
            // StationSeeder::class,
            // TeamSeeder::class,
            // TeamMemberSeeder::class,
            // TeamAtStationSeeder::class,
            // MemberResultsAtStationSeeder::class
        ]);
    }
}
