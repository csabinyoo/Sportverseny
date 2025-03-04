<?php

namespace Database\Seeders;

use App\Models\result_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResultTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'type' => 'point'
            ],
            [
                'id' => 2,
                'type' => 'time'
            ],
        ];

        if (result_type::count() === 0) {
            result_type::factory()->createMany($data);
        }
    }
}
