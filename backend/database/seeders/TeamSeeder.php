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
        if (team::count() === 0) {
            team::factory(10)->create();
        }
    }
}
